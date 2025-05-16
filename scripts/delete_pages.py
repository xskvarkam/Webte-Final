import sys
from PyPDF2 import PdfReader, PdfWriter

input_file = sys.argv[1]
output_file = sys.argv[2]
pages_to_delete = sys.argv[3]

reader = PdfReader(input_file)
writer = PdfWriter()

# Convert "2,4-5" to a set of page indexes (zero-based)
pages = set()
for part in pages_to_delete.split(','):
    if '-' in part:
        start, end = map(int, part.split('-'))
        pages.update(range(start - 1, end))
    else:
        pages.add(int(part) - 1)

# Write all pages except the ones to delete
for i in range(len(reader.pages)):
    if i not in pages:
        writer.add_page(reader.pages[i])

with open(output_file, 'wb') as f:
    writer.write(f)
