import sys
from PyPDF2 import PdfReader, PdfWriter

input_file = sys.argv[1]
output_file = sys.argv[2]
range_str = sys.argv[3]

start, end = map(int, range_str.split('-'))

reader = PdfReader(input_file)
writer = PdfWriter()

for i in range(start - 1, end):
    writer.add_page(reader.pages[i])

with open(output_file, 'wb') as f:
    writer.write(f)
