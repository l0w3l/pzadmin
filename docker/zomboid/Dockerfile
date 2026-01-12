FROM steamcmd/steamcmd:latest

ENV STEAMAPPID 380870
ENV STEAMAPP pz
ENV STEAMAPPDIR "${HOMEDIR}/${STEAMAPP}-dedicated"

# Install required packages
RUN apt-get update \
    && apt-get install -y --no-install-recommends --no-install-suggests \
      dos2unix \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Download the Project Zomboid dedicated server app using the steamcmd app
# Set the entry point file permissions
RUN mkdir -p "${STEAMAPPDIR}"
RUN chown -R "${USER}:${USER}" "${STEAMAPPDIR}"
RUN steamcmd +force_install_dir "${STEAMAPPDIR}" \
                                    +login anonymous \
                                    +app_update "${STEAMAPPID}" -beta unstable \
                                    +quit

# Copy the entry point file
COPY --chown=${USER}:${USER} scripts/entry.sh /server/scripts/entry.sh
RUN chmod 550 /server/scripts/entry.sh

# Create required folders to keep their permissions on mount
RUN mkdir -p "${HOMEDIR}/Zomboid"

WORKDIR ${HOMEDIR}

ENTRYPOINT ["/server/scripts/entry.sh"]
