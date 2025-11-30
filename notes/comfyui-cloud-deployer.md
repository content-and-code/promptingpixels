# ComfyUI Cloud Deployer

ComfyUI Cloud Deployer is a small utility that generates a one-line install script to stand up a ready-to-use ComfyUI environment on cloud GPUs. It handles version pinning, downloads models from Hugging Face and Civitai, installs popular custom nodes, and places files in the right folders for you. The result: you spend minutes configuring once, then let automation do the repetitive work every time.

## Quick Start

Use the web generator to create an install command, then run it on your cloud instance. It supports Vast.ai and RunPod out of the box, and can target local machines if you provide a path.

```bash
# 1) Open the generator
#    https://deploy.promptingpixels.com

# 2) Choose your provider (Vast.ai or RunPod), ComfyUI version, models, and custom nodes.
#    The page will produce a one-liner tailored to your selections.

# 3) Paste the generated command into your instance terminal and run it.
#    Example shape (your actual command will differ):
wget -qO setup.sh "https://deploy.promptingpixels.com/<your-config-id>.sh" && \
bash setup.sh \
  --provider vast \
  --version latest \
  --hf-token "$HF_TOKEN"
```

After the script completes, start ComfyUI from your provider’s interface. Your selected models and nodes will be available immediately (you may need one restart for new nodes to appear in menus).

## Usage

Below is a full walkthrough for a typical session on Vast.ai. The same steps apply to RunPod; just switch the provider in the generator.

```bash
# 1) Launch a GPU instance with a ComfyUI-capable image on Vast.ai.
#    Open the instance's terminal (e.g., Jupyter Terminal).

# 2) In your browser, go to the generator:
#    https://deploy.promptingpixels.com

# 3) Configure the deployment:
#    - App: ComfyUI (select the version you want, often "latest")
#    - Provider: Vast.ai
#    - Models: add a few from Hugging Face and/or Civitai
#      Example: "DreamShaper 8" (checkpoint) from Hugging Face
#    - Custom Nodes: search and add from the ComfyUI registry
#      Example: "Impact Pack"
#    - Optional Tokens:
#      - HF token for gated models (e.g., export HF_TOKEN=hf_abc... in your terminal)
#      - Civitai token if required for private files

# 4) Copy the one-liner created by the site and paste it into your Vast.ai terminal.
#    Here’s what a typical run might look like with explanatory comments:

# Optional: set tokens for gated downloads
export HF_TOKEN="hf_xxx_your_token"
export CIVITAI_TOKEN="civ_xxx_if_needed"

# Run the generated command (example shape; your link and flags will differ)
wget -qO setup.sh "https://deploy.promptingpixels.com/<your-config-id>.sh" && \
bash setup.sh \
  --provider vast \            # aligns paths with Vast.ai images
  --version 0.2.3 \            # pins ComfyUI version (or use 'latest')
  --models "hf:org/DreamShaper-8:checkpoint" \
  --nodes "comfyorg/impact-pack" \
  --hf-token "$HF_TOKEN" \
  --civitai-token "$CIVITAI_TOKEN"

# 5) When the script finishes, launch ComfyUI from your provider dashboard.
#    - Checkpoints land in the checkpoints directory
#    - LoRAs go into the LoRA directory
#    - Custom nodes are cloned and installed
#    If nodes don’t appear, restart ComfyUI once.
```

Tip: If you frequently recreate the same environment, save a preset in the generator and reuse it to reproduce the exact stack (models, nodes, and ComfyUI version).

## Configuration

The generator emits a script that accepts a small set of flags and environment variables. Here are the most common options:

| Key | Required | Description | Example |
|-----|----------|-------------|---------|
| provider | Yes | Target platform; adjusts paths and startup behavior | vast, runpod, local |
| version | No | ComfyUI version to install (use semantic tag or latest) | 0.2.3, latest |
| models | No | Comma- or space-separated model specs (placed into correct folders) | hf:org/DreamShaper-8:checkpoint, civitai:12345:lorA |
| nodes | No | Comma- or space-separated custom node repos from the ComfyUI registry | comfyorg/impact-pack, someuser/sdxl-nodes |
| install_dir | No | Override install path (useful for local setups) | /opt/comfyui, C:\ComfyUI |
| hf_token | No | Hugging Face token for gated files | hf_xxx... |
| civitai_token | No | Civitai token for private or rate-limited downloads | civ_xxx... |
| preset | No | Load a saved config bundle from the generator | qwen-image-edit, my-default-stack |

Notes:
- Models are auto-routed to the right subfolders (checkpoints, LoRA, embeddings, controlnets, etc.).
- On local or custom environments, set install_dir so the script knows where ComfyUI lives.

## How It Works

The web tool composes a deterministic bash script based on your selections. It performs provider-aware setup, ensures the requested ComfyUI version is present, installs and updates custom nodes from the registry, and pulls models from Hugging Face and Civitai into the correct directories. You can click “Full Script” in the generator to inspect or copy the entire script, then tailor it to your needs.

Because the script encodes all configuration, you get fast, repeatable environments on disposable GPU instances or across machines—no more manual model shuffling, node installs, or version mismatch surprises.