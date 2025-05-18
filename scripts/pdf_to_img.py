import sys
import fitz  # PyMuPDF
import os
import shutil
import zipfile

input_path = sys.argv[1]
output_zip_path = sys.argv[2]

temp_dir = "temp_images"
os.makedirs(temp_dir, exist_ok=True)

pdf = fitz.open(input_path)

for page_number in range(len(pdf)):
    page = pdf.load_page(page_number)
    pix = page.get_pixmap(dpi=150)
    output_file = os.path.join(temp_dir, f"page_{page_number + 1}.jpg")
    pix.save(output_file)

with zipfile.ZipFile(output_zip_path, 'w') as zipf:
    for file in os.listdir(temp_dir):
        zipf.write(os.path.join(temp_dir, file), arcname=file)

shutil.rmtree(temp_dir)
