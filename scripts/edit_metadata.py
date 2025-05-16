import sys
from PyPDF2 import PdfReader, PdfWriter

input_file = sys.argv[1]
output_file = sys.argv[2]
new_title = sys.argv[3]
new_author = sys.argv[4] if len(sys.argv) > 4 else ""

reader = PdfReader(input_file)
writer = PdfWriter()

for page in reader.pages:
    writer.add_page(page)

metadata = reader.metadata or {}
metadata.update({
    '/Title': new_title,
    '/Author': new_author,
})

writer.add_metadata(metadata)

with open(output_file, "wb") as f:
    writer.write(f)
