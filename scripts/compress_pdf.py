import sys
import fitz  # PyMuPDF

input_file = sys.argv[1]
output_file = sys.argv[2]

doc = fitz.open(input_file)

# Compress images in each page
for page in doc:
    for img in page.get_images(full=True):
        xref = img[0]
        doc.save(output_file, garbage=4, deflate=True, clean=True)
