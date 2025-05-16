import sys
from PyPDF2 import PdfReader

input_file = sys.argv[1]
output_file = sys.argv[2]

reader = PdfReader(input_file)
text = ""

for page in reader.pages:
    text += page.extract_text() or ""
    text += "\n\n"

with open(output_file, "w", encoding="utf-8") as f:
    f.write(text.strip())
