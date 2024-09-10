---
layout: default
title: Code Examples
parent: posts
---

# Code Examples

Collection of code to help understand what is going on with diffusion models.

## [What is CLIP?](https://github.com/content-and-code/promptingpixels/blob/main/docs/code_examples/what_is_clip.ipynb)

This notebook was exploring how a prompt is broken apart by tokens:

```
torch.Size([1, 77])
49406:<|startoftext|>
6052:illustration</w>
539:of</w>
320:a</w>
2368:cat</w>
593:with</w>
320:a</w>
14295:wizard</w>
```

Then how its converted to an embedding (e.g. 1536 values) that is then sent to the model.

## [Vast.ai Setup Script](https://github.com/content-and-code/promptingpixels/blob/main/docs/code_examples/default.sh)

This is a modified version of the [ai-dock/stable-diffusion-webui](https://github.com/ai-dock/stable-diffusion-webui) setup script.

Instead of downloading models, ControlNets, etc. that I don't intend to use, it was modified to only download what was needed at the time.  Use and adapt on it if you want faster setups.
