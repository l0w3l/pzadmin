#!/bin/bash

rm -f /root/Zomboid/.server_healthy

cd ${STEAMAPPDIR}

#####################################
#                                   #
# Force an update if the env is set #
#                                   #
#####################################

if [ "${FORCEUPDATE}" == "1" ]; then
  echo "FORCEUPDATE variable is set, so the server will be updated right now"
  bash "${STEAMCMDDIR}/steamcmd.sh" +force_install_dir "${STEAMAPPDIR}" +login anonymous +app_update "${STEAMAPPID}" -beta 42.13.1 validate +quit
fi


######################################
#                                    #
# Process the arguments in variables #
#                                    #
######################################
ARGS=""

# Set the server memory. Units are accepted (1024m=1Gig, 2048m=2Gig, 4096m=4Gig): Example: 1024m
if [ -n "${ZOMBOID_MEMORY}" ]; then
  ARGS="${ARGS} -Xmx${ZOMBOID_MEMORY} -Xms${ZOMBOID_MEMORY}"
fi

# Option to perform a Soft Reset
# if [ "${SOFTRESET}" == "1" ] || [ "${SOFTRESET,,}" == "true" ]; then
#   ARGS="${ARGS} -Dsoftreset"
# fi

# End of Java arguments
ARGS="${ARGS} -- "

# Disables Steam integration on server.
# - Default: Enabled
if [ "${ZOMBOID_NO_STEAM}" == "1" ] || [ "${ZOMBOID_NO_STEAM,,}" == "true" ]; then
  ARGS="${ARGS} -nosteam"
fi

# Sets the path for the game data cache dir.
# - Default: ~/Zomboid
# - Example: /server/Zomboid/data
if [ -n "${ZOMBOID_CACHE_DIR}" ]; then
  ARGS="${ARGS} -cachedir=${ZOMBOID_CACHE_DIR}"
fi

# Option to control where mods are loaded from and the order. Any of the 3 keywords may be left out and may appear in any order.
# - Default: workshop,steam,mods
# - Example: mods,steam
if [ -n "${ZOMBOID_MOD_FOLDERS}" ]; then
  ARGS="${ARGS} -modfolders ${ZOMBOID_MOD_FOLDERS}"
fi

# Launches the game in debug mode.
# - Default: Disabled
if [ "${ZOMBOID_DEBUG}" == "1" ] || [ "${ZOMBOID_DEBUG,,}" == "true" ]; then
  ARGS="${ARGS} -debug"
fi

# Option to bypasses the enter-a-password prompt when creating a server.
# This option is mandatory the first startup or will be asked in console and startup will fail.
# Once is launched and data is created, then can be removed without problem.
# Is recommended to remove it, because the server logs the arguments in clear text, so Admin password will be sent to log in every startup.
if [ -n "${ZOMBOID_ADMIN_PASSWORD}" ]; then
  ARGS="${ARGS} -adminpassword ${ZOMBOID_ADMIN_PASSWORD}"
fi

# Server password
if [ -n "${ZOMBOID_SERVER_PASSWORD}" ]; then
  ARGS="${ARGS} -password ${ZOMBOID_SERVER_PASSWORD}"
fi

# You can choose a different servername by using this option when starting the server.
if [ -n "${ZOMBOID_SERVER_NAME}" ]; then
  ARGS="${ARGS} -servername ${ZOMBOID_SERVER_NAME}"
else
  # If not servername is set, use the default name in the next step
  ZOMBOID_SERVER_NAME="servertest"
fi

# If preset is set, then the config file is generated when it doesn't exists or SERVERPRESETREPLACE is set to True.
if [ -n "${ZOMBOID_SERVER_PRESET}" ]; then
  # If preset file doesn't exists then show an error and exit
  if [ ! -f "${STEAMAPPDIR}/media/lua/shared/Sandbox/${ZOMBOID_SERVER_PRESET}.lua" ]; then
    echo "*** ERROR: the preset ${ZOMBOID_SERVER_PRESET} doesn't exists. Please fix the configuration before start the server ***"
    exit 1
  # If SandboxVars files doesn't exists or replace is true, copy the file
  elif [ ! -f "${HOMEDIR}/Zomboid/Server/${ZOMBOID_SERVER_NAME}_SandboxVars.lua" ] || [ "${ZOMBOID_SERVER_PRESETREPLACE,,}" == "true" ]; then
    echo "*** INFO: New server will be created using the preset ${ZOMBOID_SERVER_PRESET} ***"
    echo "*** Copying preset file from \"${STEAMAPPDIR}/media/lua/shared/Sandbox/${ZOMBOID_SERVER_PRESET}.lua\" to \"${HOMEDIR}/Zomboid/Server/${ZOMBOID_SERVER_NAME}_SandboxVars.lua\" ***"
    mkdir -p "${HOMEDIR}/Zomboid/Server/"
    cp -nf "${STEAMAPPDIR}/media/lua/shared/Sandbox/${ZOMBOID_SERVER_PRESET}.lua" "${HOMEDIR}/Zomboid/Server/${ZOMBOID_SERVER_NAME}_SandboxVars.lua"
    sed -i "1s/return.*/SandboxVars = \{/" "${HOMEDIR}/Zomboid/Server/${ZOMBOID_SERVER_NAME}_SandboxVars.lua"
    # Remove carriage return
    dos2unix "${HOMEDIR}/Zomboid/Server/${ZOMBOID_SERVER_NAME}_SandboxVars.lua"
    # I have seen that the file is created in execution mode (755). Change the file mode for security reasons.
    chmod 644 "${HOMEDIR}/Zomboid/Server/${ZOMBOID_SERVER_NAME}_SandboxVars.lua"
  fi
fi

# Option to handle multiple network cards. Example: 127.0.0.1
if [ -n "${ZOMBOID_IP}" ]; then
  ARGS="${ARGS} ${ZOMBOID_IP} -ip ${ZOMBOID_IP}"
fi

# Set the DefaultPort for the server. Example: 16261
if [ -n "${ZOMBOID_PORT_1}" ]; then
  ARGS="${ARGS} -port ${ZOMBOID_PORT_1}"
fi

# Set the DefaultUDPPort for the server. Example: 16261
if [ -n "${ZOMBOID_PORT_2}" ]; then
  ARGS="${ARGS} -udpport ${ZOMBOID_PORT_2}"
fi

# Option to enable/disable VAC on Steam servers. On the server command-line use -steamvac true/false. In the server's INI file, use STEAMVAC=true/false.
if [ -n "${ZOMBOID_STEAM_VAC}" ]; then
  ARGS="${ARGS} -steamvac ${ZOMBOID_STEAM_VAC,,}"
fi

# Steam servers require two additional ports to function (I'm guessing they are both UDP ports, but you may need TCP as well).
# These are in addition to the DefaultPort= setting. These can be specified in two ways:
#  - In the server's INI file as SteamPort1= and SteamPort2=.
#  - Using STEAMPORT1 and STEAMPORT2 variables.
if [ -n "${ZOMBOID_STEAM_PORT_1}" ]; then
  ARGS="${ARGS} -steamport1 ${ZOMBOID_STEAM_PORT_1}"
fi
if [ -n "${ZOMBOID_STEAM_PORT_2}" ]; then
  ARGS="${ARGS} -steamport2 ${ZOMBOID_STEAM_PORT_2}"
fi

if [ -n "${ZOMBOID_SERVER_PASSWORD}" ]; then
	sed -i "s/Password=.*/Password=${ZOMBOID_SERVER_PASSWORD}/" "${HOMEDIR}/Zomboid/Server/${ZOMBOID_SERVER_NAME}.ini"
fi

if [ -n "${ZOMBOID_MOD_IDS}" ]; then
 	echo "*** INFO: Found Mods including ${ZOMBOID_MOD_IDS} ***"
	sed -i "s/Mods=.*/Mods=${ZOMBOID_MOD_IDS}/" "${HOMEDIR}/Zomboid/Server/${ZOMBOID_SERVER_NAME}.ini"
fi

if [ -n "${ZOMBOID_WORKSHOP_IDS}" ]; then
 	echo "*** INFO: Found Workshop IDs including ${ZOMBOID_WORKSHOP_IDS} ***"
	sed -i "s/WorkshopItems=.*/WorkshopItems=${ZOMBOID_WORKSHOP_IDS}/" "${HOMEDIR}/Zomboid/Server/${ZOMBOID_SERVER_NAME}.ini"
fi


# Fix to a bug in start-server.sh that causes to no preload a library:
# ERROR: ld.so: object 'libjsig.so' from LD_PRELOAD cannot be preloaded (cannot open shared object file): ignored.
export LD_LIBRARY_PATH="${STEAMAPPDIR}/jre64/lib:${LD_LIBRARY_PATH}"

## Fix the permissions in the data and workshop folders
chown -R 1000:1000 "${STEAMAPPDIR}/steamapps/workshop" "${HOMEDIR}/Zomboid"

su - root -c "export LD_LIBRARY_PATH=\"${STEAMAPPDIR}/jre64/lib:${LD_LIBRARY_PATH}\" && cd ${STEAMAPPDIR} && pwd && ./start-server.sh ${ARGS}"
