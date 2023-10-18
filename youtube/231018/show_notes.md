# Understanding the Difference Between Stable Diffusion Models

Per [Wikipedia](https://en.wikipedia.org/wiki/Stable_Diffusion#Releases), Stable Diffusion current releases are as follows:

| Version number | Release date |
| --- | --- |
| 1.0 |  |
| 1.4 | August 2022 |
| 1.5 | October 2022 |
| 2.0 | November 2022 |
| 2.1 | December 2022 |
| XL 1.0 | July 2023 |

## SD 1.x

SD 1.4 and 1.5 are very beginner friendly. You can just download the checkpoint and place in the modes/Stable-diffusion folder and you are good to go.  

- **Resolution requirements**: 512x512
- **Prompts**: These models depend on [OpenAI's ViT-L/14](https://huggingface.co/openai/clip-vit-large-patch14) which was released in January 2021.  Here is the original blog post by OpenAI: https://openai.com/research/clip
- **1.4 Model Card**: [You can find it here on Huggingface to download the checkpoint](https://huggingface.co/CompVis/stable-diffusion-v-1-4-original). Per [Tanishq Mathew Abraham, PhD](https://twitter.com/iScienceLuvr/status/1601011140934664193), a Research Director with Stability AI, it is recommended to use the EMA checkpoint (sd-v1-4-full-ema.ckpt) for image generation.  
- **1.5 Model Card**: This was released in via Runway, a partner of Stability AI, like 1.4, [the checkpoint is available on Huggingface](https://huggingface.co/runwayml/stable-diffusion-v1-5). For generation, go with with v1-5-pruned-emaonly.ckpt as it is good for just simple generation and uses less ram.  v1-5-pruned.ckpt is for fine tuning the model further.

EMA stands for Exponential Moving Averaging, basically that there is more weight on the most recent values.  This is a common technique in machine learning to reduce noise and improve accuracy.

### Example Images

Prompt: a woman, grassy field, holding a flower, facing viewer, photo
Negative Prompt: illustration, cartoon, painting
Resolution: 512 x 512

**1.4 Result**:

[INSERT IMAGE]

**1.5 Result**:

[INSERT IMAGE]

## SD 2.x

Released in late 2022, Stable Diffusion 2.0 and 2.1 brought some notable advancements in quality, resolution, and prompt interpretation.

- **Resolution requirements**: 768x768
- **Prompts**: These models now use LAION's OpenCLIP-ViT/H for prompt interpretation resulting in [shorter prompts that can be more expressive when compared to 1.x](https://stability.ai/blog/stablediffusion2-1-release7-dec-2022).
- **2.0 Model Card**: The [original release of 2.0 can be found on HuggingFace](https://huggingface.co/stabilityai/stable-diffusion-2) (768-v-ema). 
- **2.1 Model Card**: 2.1 is [also available on HuggingFace](https://huggingface.co/stabilityai/stable-diffusion-2-1). However, Stability AI includes multiple checkpoint files including v2-1_768-ema-pruned and v2-1_768-nonema-pruned (both in a .ckpt and .safetensors format).
- **YAML Config Required**: When using the new 2.x models, you must include a config file with the same name as the checkpoint file.  For example, if you are using v2-1_768-ema-pruned.ckpt, you must also include v2-1_768-ema-pruned.yaml in the same folder (stable-diffusion-webui/models/Stable-diffusion).  The .yaml file can be found [in the Stability AI GitHub repo](https://github.com/Stability-AI/stablediffusion/blob/main/configs/stable-diffusion/v2-inference-v.yaml). 

### Example Images

Prompt: a woman, grassy field, holding a flower, facing viewer, photo
Negative Prompt: illustration, cartoon, painting
Resolution: 512 x 512

**2.0 Result**:

[INSERT IMAGE]

**2.1 Result**:

[INSERT IMAGE]

## SD XL 1.0

The latest release, SD XL is the latest release from Stability AI. This model requires much more resources to run locally. 

- **Resolution requirements**: 1024x1024
- **Prompts**: [Per the repo](https://github.com/Stability-AI/generative-models), SD XL uses OpenCLIP-ViT/G and CLIP-ViT/L - an improvement over the previous models. This allows for better inference on prompts.  [As stated by Stability AI](https://stability.ai/blog/stable-diffusion-sdxl-1-announcement):

> Furthermore, SDXL can understand the differences between concepts like “The Red Square” (a famous place) vs a “red square” (a shape).

-**XL 1.0 Model Card**: [The model card can be found on HuggingFace](https://huggingface.co/stabilityai/stable-diffusion-xl-base-1.0).  Unlike 2.x, SD XL does not require a separate .yaml file.  Size of the weights is notably larger at nearly 7 GB (previous models were between 4-5 GB).

To run SD XL you'll need a dedicated GPU.  Attempting to run on an Apple M1 Pro chip with 16 GB of RAM resulted a RuntimeError due to insufficient memory.  

Options for running SD XL include:
- **Clipdrop (by Stability AI)**: This requires a monthly subscription at $9/mo.
- **Cloud Based Services**: RunDiffusion, ThinkDiffusion
- **Rental GPU Services**: Vast.ai, RunPod, TensorDock

### Example Images



========= PREVIOUS NOTES: =========

 LAION's OpenCLIP-ViT/H for prompt interpretation resulting in [shorter prompts that can be more expressive when compared to 1.x](https://stability.ai/blog/stablediffusion2-1-release7-dec-2022).

Reddit has a good comparison between 1.x and 2.x: https://www.reddit.com/r/StableDiffusion/comments/zfra79/comparison_of_15_20_and_21/

sd_xl_base_1.0.safetensors - caused the following error:

RuntimeError: MPS backend out of memory (MPS allocated: 15.14 GB, other allocations: 2.00 GB, max allowed: 18.13 GB). Tried to allocate 1024.00 MB on private pool. Use PYTORCH_MPS_HIGH_WATERMARK_RATIO=0.0 to disable upper limit for memory allocations (may cause system failure).

Apparently SD XL can work without a refiner or in the comfyui interface - hoewver, uncertain how to get it to work in vanilla Automatic1111 WebUI.


Attempting to run sdXL_v10VAEFix.safetensors to see if the VAE fix helps at all.  I think just the opposite is true.  VAE i believe adds more refinement to the image.

In testing, the latter model took 385.2s to load the weights.  sd_xl_base_1 took only 149.6s.  Basically SD XL just doesn't work on vanilla Automatic1111 WebUI.  
