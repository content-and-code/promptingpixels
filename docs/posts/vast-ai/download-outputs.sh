#!/bin/bash

# Function to fetch the only running vast.ai instance ID
get_running_instance_id() {
  vastai show instances | awk '/running/ {print $1; exit}'
}


# Function to zip the outputs folder with a specific name
zip_outputs_folder() {
  INSTANCE_ID=$1
  TIMESTAMP=$(date +%y%m%d)
  ZIP_NAME="${TIMESTAMP}_${INSTANCE_ID}_outputs.zip"
  LOCAL_DESTINATION=$2
  REMOTE_FILE_PATH=$3

  # Construct the SCP command to download and zip the directory
  SCP_URL=$(vastai scp-url $INSTANCE_ID --raw)
  USERNAME=$(echo $SCP_URL | awk -F '[/@:]' '{print $4}')
  HOSTNAME=$(echo $SCP_URL | awk -F '[/@:]' '{print $5}')
  PORT=$(echo $SCP_URL | awk -F '[/@:]' '{print $6}')

  # Change to the directory where you want to save the zip file
  cd $LOCAL_DESTINATION

  # Construct the command to SSH into the remote machine, zip the directory, and then exit
  ZIP_COMMAND="ssh -p $PORT ${USERNAME}@${HOSTNAME} 'cd $REMOTE_FILE_PATH && zip -r $ZIP_NAME .'"
  
  # Execute the ZIP command
  echo "Running ZIP command: $ZIP_COMMAND"
  eval $ZIP_COMMAND

  # Construct the SCP command to download the zip file
  SCP_COMMAND="scp -P $PORT ${USERNAME}@${HOSTNAME}:${REMOTE_FILE_PATH}/${ZIP_NAME} ."

  # Execute the SCP command to download the zip file
  echo "Running SCP command: $SCP_COMMAND"
  eval $SCP_COMMAND
}

# Main script execution
REMOTE_OUTPUTS_PATH="/workspace/stable-diffusion-webui/outputs"
LOCAL_DOWNLOAD_PATH="/Users/shawn/Downloads"

# Get the running instance ID
INSTANCE_ID=$(get_running_instance_id)
if [ -z "$INSTANCE_ID" ]; then
  echo "No running instance found."
  exit 1
fi

# Call the function to zip and download the outputs folder
zip_outputs_folder $INSTANCE_ID $LOCAL_DOWNLOAD_PATH $REMOTE_OUTPUTS_PATH