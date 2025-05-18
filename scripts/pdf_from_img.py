import sys
from reportlab.lib.pagesizes import A4
from reportlab.pdfgen import canvas
from PIL import Image
import os

output_pdf = sys.argv[1]
image_paths = sys.argv[2:]

c = canvas.Canvas(output_pdf, pagesize=A4)
width, height = A4

for img_path in image_paths:
    img = Image.open(img_path)
    img_width, img_height = img.size

    # Pomery strán
    ratio = min(width / img_width, height / img_height)
    new_width = img_width * ratio
    new_height = img_height * ratio
    x = (width - new_width) / 2
    y = (height - new_height) / 2

    c.drawImage(img_path, x, y, width=new_width, height=new_height)
    c.showPage()

c.save()
