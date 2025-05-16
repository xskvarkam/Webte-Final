import sys
from PyPDF2 import PdfReader, PdfWriter

input_path = sys.argv[1]
output_path = sys.argv[2]

reader = PdfReader(input_path)
writer = PdfWriter()

# Add pages in reverse order
for page in reversed(reader.pages):
    writer.add_page(page)

with open(output_path, "wb") as f:
    writer.write(f)
