---
title: Deploying ComfyUI Workflows
layout: default
parent: docs
---

# Deploying ComfyUI Workflows

Deploy ComfyUI and other AI tools on Vast.ai or RunPod using the [Prompting Pixels deployment tool](https://deploy.promptingpixels.com/).

## Quick Start

1. Visit [deploy.promptingpixels.com](https://deploy.promptingpixels.com/)
2. Click "Create a New Instance"
3. Upload your configuration file or use our examples:
   - [Simple ControlNet Example](./simple-controlnet.json)

## Configuration

Create a JSON file to specify your deployment settings:

```json
{
  "provider": "vast_ai",  // or "runpod"
  "gpu_requirements": {
    "min_vram": 12,
    "min_cuda": "11.8"
  },
  "comfyui": {
    "version": "latest",
    "models": [
      "dreamshaper_8.safetensors",
      "sd_xl_base_1.0.safetensors"
    ],
    "custom_nodes": [
      "ComfyUI-Advanced-ControlNet",
      "ComfyUI-Impact-Pack"
    ],
    "workflows": [
      "path/to/your/workflow.json"
    ]
  }
}
```

## Features

- **Smart GPU Selection**: Browse Vast.ai marketplace with real-time availability
- **Model Management**: Browse HuggingFace and Civitai with automatic path configuration
- **Pre-configured**: Popular models, extensions, and optimized settings included

## Requirements

- Vast.ai or RunPod account
- Provider API key
- JSON configuration file

## Example Configurations

- [Simple ControlNet](./simple-controlnet.json) - Basic setup with ControlNet support

## Additional Resources

- [Vast.ai Documentation](https://vast.ai/docs/)
- [RunPod Documentation](https://www.runpod.io/docs)
- [ComfyUI GitHub](https://github.com/comfyanonymous/ComfyUI) 