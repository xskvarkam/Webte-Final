import sys
from PyPDF2 import PdfReader, PdfWriter

input_file = sys.argv[1]
output_file = sys.argv[2]
pages_arg = sys.argv[3]
angle = int(sys.argv[4])

reader = PdfReader(input_file)
writer = PdfWriter()

# Parse page numbers to rotate
rotate_pages = set()
for part in pages_arg.split(','):
    if '-' in part:
        start, end = map(int, part.split('-'))
        rotate_pages.update(range(start - 1, end))
    else:
        rotate_pages.add(int(part) - 1)

for i, page in enumerate(reader.pages):
    if i in rotate_pages:
        page.rotate(angle)
    writer.add_page(page)

with open(output_file, 'wb') as f:
    writer.write(f)
