FROM steamcmd/steamcmd:latest

ENV STEAMAPPID 380870
ENV STEAMAPP pz
ENV STEAMAPPDIR "${HOMEDIR}/${STEAMAPP}-dedicated"

ARG ZOMBOID_BETA

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

# Copy the entry point file
COPY --chown=${USER}:${USER} scripts/entry.sh /server/scripts/entry.sh
RUN chmod 550 /server/scripts/entry.sh

# Create required folders to keep their permissions on mount
RUN mkdir -p "${HOMEDIR}/Zomboid"

WORKDIR ${HOMEDIR}

ENTRYPOINT ["/bin/sh", "-c", "/server/scripts/entry.sh"]
