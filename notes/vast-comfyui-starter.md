# Vast ComfyUI Starter

Spin up ComfyUI on Vast.ai in minutes and get straight to generating images without babysitting installs. This starter gives you a reliable path to a working ComfyUI instance, shows where to put models, and helps you avoid common bandwidth and storage pitfalls when renting cloud GPUs.

## Quick Start

Provision a GPU on Vast.ai using the official ComfyUI template, then add models through the Jupyter Terminal that comes with the instance. The template ships with ComfyUI, a web UI, a Jupyter Notebook, and a terminal—no manual CUDA or driver setup required.

```bash
# Once your Vast.ai instance is running, click "Open" -> "Launch Application" -> "Jupyter Terminal"
# Then run the following to set up model folders and add an example model

cd /workspace/ComfyUI

# Ensure expected directories exist
mkdir -p models/checkpoints models/loras models/vae models/clip models/upscale_models models/controlnet

# Optional: set your Hugging Face token if you plan to pull gated weights
export HF_TOKEN="hf_xxx..."

# Example: download a base model to the correct folder (replace with the model you actually want)
wget -O models/checkpoints/sd15.safetensors \
  https://huggingface.co/runwayml/stable-diffusion-v1-5/resolve/main/v1-5-pruned-emaonly.safetensors

# Refresh ComfyUI's model list in the browser by pressing "R"
```

Tip: when choosing a GPU offer, watch bandwidth pricing in the offer details. Some hosts charge per-terabyte egress; if you plan to pull large models, sort/filter for low TB pricing first.

## Usage

The most common workflow is: rent a GPU, open ComfyUI, add models, run a workflow, then download outputs. Below is a complete example that adds a base model and a LoRA, runs ComfyUI, and bundles your outputs for download.

```bash
# 1) Add models via Jupyter Terminal
cd /workspace/ComfyUI

# Create target folders (safe if they already exist)
mkdir -p models/checkpoints models/loras models/vae

# Download a base checkpoint (replace with your preferred model URL)
wget -O models/checkpoints/base.safetensors \
  https://huggingface.co/runwayml/stable-diffusion-v1-5/resolve/main/v1-5-pruned-emaonly.safetensors

# Download a LoRA (example URL; change to your model of choice)
wget -O models/loras/style-lora.safetensors \
  https://civitai.com/api/download/models/12345

# Optional: add a VAE if the model requires one
# wget -O models/vae/model.vae.safetensors https://huggingface.co/.../vae.safetensors

# 2) In your browser, click "Open" on the Vast.ai instance, then "Launch Application" -> "ComfyUI"
#    In ComfyUI, press "R" to reload the model list and load your graph/template.

# 3) Generate images as usual. Outputs land here by default:
#    /workspace/ComfyUI/output

# 4) Bundle outputs for an easy multi-file download
cd /workspace/ComfyUI/output
tar -czf ~/comfyui-outputs.tar.gz *.png 2>/dev/null || true
tar -czf ~/comfyui-outputs-with-subfolders.tar.gz *

# 5) In Jupyter's file browser, navigate to your home directory (~) and download the tar.gz file(s).
```

Notes:
- If the ComfyUI tab doesn’t load immediately after launch, wait 60–90 seconds and refresh; services can take a moment to start.
- Out-of-memory errors typically mean reduce resolution/batch size or switch to a GPU with more VRAM.

## Configuration

You can control common settings with environment variables or a simple config file if you script your setup. These keys mirror what most users care about when launching on Vast.ai and organizing models.

| Key | Required | Description | Example |
|-----|----------|-------------|---------|
| provider | Yes | Cloud provider to use | vast |
| template | Yes | Image/template to run | comfyui-official |
| storage_gb | Yes | Container disk size to allocate | 200 |
| gpu | No | Preferred GPU family or VRAM target | 4090, 3090, 24GB+ |
| bandwidth_max_tb_cost | No | Max acceptable egress price in $/TB | 0.50 |
| token | No | Auth token for gated models | hf_xxx... |
| model_urls | No | List of model URLs to fetch on boot | https://huggingface.co/.../model.safetensors |
| output_dir | No | Where generated files are written | /workspace/ComfyUI/output |

Recommended defaults:
- storage_gb: 200 (comfortable headroom for several models)
- bandwidth_max_tb_cost: keep low if you expect heavy downloads
- gpu: any recent high-VRAM consumer card (e.g., RTX 4090) works well for most workflows

## How It Works

Vast.ai hosts a marketplace of GPUs from different providers. When you rent an instance with the official ComfyUI template, the machine boots with ComfyUI, a web UI, an API wrapper, Jupyter, and a terminal already configured. You add models into specific folders under /workspace/ComfyUI/models, refresh the UI, and start generating.

A few operational insights help keep things smooth and cost-effective:
- Bandwidth matters: providers can set per-terabyte transfer fees. If you expect to download large models (Flux, Wan, Hunyuan Video, etc.), pick hosts with low egress pricing to avoid surprises.
- Storage vs compute: stopping an instance pauses GPU charges but continues billing for the attached storage; destroying an instance stops all billing but erases your setup.
- Disk sizing: you can’t resize storage on a running instance. If you outgrow your disk, save outputs, stop the instance, and relaunch a new one with a larger disk.
- Reliability: if a machine behaves oddly after boot, it’s often faster to destroy and pick another host than to troubleshoot a bad node.

That’s all you need to go from zero to a functioning ComfyUI environment on Vast.ai—pick a GPU, launch the template, drop models into place, and create.