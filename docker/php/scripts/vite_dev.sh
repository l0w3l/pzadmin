#!/bin/bash

scripts_folder=/usr/local/bin/scripts

bash "$scripts_folder"/wait_setup.sh

while getopts 'd:' opt; do
    case "$opt" in
        d)
            arg="$OPTARG"
            if [ "$arg" == "true" ]; then
                bash "$scripts_folder"/processes/run_dev_vite.sh
            fi
            ;;
        *)
          ;;
    esac
done
