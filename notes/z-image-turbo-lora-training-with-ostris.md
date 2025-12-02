# Z-Image Turbo LoRA Training with Ostris

This project walks you through training a LoRA for Alibaba’s Z-Image Turbo model using the Ostris AI Toolkit on a cloud GPU. The goal is to get a custom subject or style working quickly, even on modest hardware, and produce a reusable LoRA that you can plug into your image workflows.

## Quick Start

Spin up the toolkit, prep a small dataset, and kick off training. The example below uses RunPod with the official Ostris AI Toolkit template.

```bash
# 1) Prepare a small dataset locally (8–20 images works well)
mkdir -p dataset/teach3r
# Add your JPG/PNG images into dataset/teach3r (one subject or consistent theme)

# Optional: create a caption file per image if you want stronger control over prompts
# e.g., dataset/teach3r/img_001.jpg.txt

# 2) Zip your dataset for easy upload
(cd dataset && zip -r teach3r.zip teach3r)

# 3) Deploy the Ostris AI Toolkit on RunPod (via web UI)
# - Choose an On-Demand GPU (e.g., RTX 5090 or similar)
# - Select the 'Ostris AI Toolkit (latest)' template
# - Keep default disk (or increase if you have large datasets)
# - Start the pod and open the web UI

# 4) In Ostris -> Datasets: click 'New Dataset' and upload teach3r.zip
# (Or create a dataset and drag-drop your images directly)
```

## Usage

Below is a full, end-to-end example that trains a single-subject LoRA called “teach3r,” generates progress samples during training, and downloads the resulting checkpoint when complete.

```bash
# Open the Ostris AI Toolkit in your browser (RunPod will give you the URL/port)

# A) Create a dataset
# - Navigate to Datasets
# - New Dataset -> Name: teach3r
# - Upload your images (captions optional)

# B) Create a training job
# - New Job:
#   Training Name: teach3r
#   Trigger Word: teach3r
#   Model Architecture: Z-Image Turbo
#   Training Adapter Path: training_adapter_v1.safetensors
#     # Tip: A new adapter is being tested; switch to training_adapter_v2.safetensors to try it.
#
# - Dataset:
#   Select the "teach3r" dataset you created
#   Resolution: 1024x1024
#
# - Steps:
#   Total Steps: 3000
#   (On an RTX 5090, expect roughly ~1 hour with defaults)
#
# - Samples (optional but useful):
#   Sample Every: 250 steps
#   Add prompts that use your trigger and LoRA scale, for example:
#     "<lora:teach3r:0.9>, a school teacher shooting a basketball, smiling, outdoors, daytime"
#     "<lora:teach3r:0.8>, portrait, soft lighting, shallow depth of field"
#   # These images let you see when the LoRA starts to “take” during training.

# C) Start training
# - Go to Training Queue
# - Press Play, then Start

# D) Monitor and download
# - Watch GPU/CPU and Samples to track progress
# - When complete: Overview -> Checkpoints -> download the latest .safetensors

# E) Use your LoRA in your workflow (example prompt syntax)
# - Many UIs accept this format: <lora:teach3r:0.8>
# - Example: "<lora:teach3r:0.8>, a school teacher shooting a basketball, smiling"
# - Adjust the scale (e.g., 0.6–1.0) to control strength
```

## Configuration

The most common knobs you’ll set while training with Ostris. Values below mirror what you’ll see in the UI.

| Key | Required | Description | Example |
|-----|----------|-------------|---------|
| provider | Yes | Where you run the toolkit | runpod |
| gpu | Yes | GPU type or tier | RTX 5090 |
| disk_gb | Yes | Disk space for datasets and outputs | 200 |
| model_architecture | Yes | Model to fine-tune | z-image-turbo |
| training_adapter_path | Yes | LoRA training adapter file | training_adapter_v1.safetensors |
| dataset_name | Yes | Target dataset created in Ostris | teach3r |
| resolution | Yes | Training resolution (square recommended) | 1024x1024 |
| steps | Yes | Number of training steps | 3000 |
| batch_size | No | Images per step (VRAM dependent) | 1 |
| learning_rate | No | Optimizer learning rate | 1e-4 |
| sample_every | No | Interval to render preview images | 250 |
| sample_prompts | No | Prompts to test LoRA during training | <lora:teach3r:0.9>, portrait... |
| trigger_word | Yes | Token to invoke your LoRA concept | teach3r |
| lora_rank | No | Rank for LoRA adapter (trade-off: quality/VRAM) | 16 |
| output_checkpoint_name | No | Name for the produced file | teach3r.safetensors |

## How It Works

- Z-Image Turbo is a distilled image model designed to run efficiently while producing strong results. Instead of retraining the whole network, you fine-tune a compact LoRA that captures your new subject or style.
- The Ostris AI Toolkit orchestrates the process: you define a dataset, choose Z-Image Turbo, pick a training adapter, and run for a fixed number of steps. Optional sample prompts render progress images so you can see when the LoRA “locks in.”
- At inference time, you load the LoRA alongside the base model and dial its influence with a scale (for example, <lora:teach3r:0.8>). The training adapter has versions (v1 and a v2 under testing); switching paths lets you try the latest improvements without changing the rest of your setup.

Tips:
- Keep your dataset focused and consistent to avoid conflicting signals.
- Start with 1024x1024 and ~3000 steps; adjust up or down based on results and VRAM.
- If outputs look too strong or off-model, reduce the LoRA scale during inference (e.g., 0.6–0.8).