import sys
from PyPDF2 import PdfReader, PdfWriter
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import letter
import io

input_file = sys.argv[1]
output_file = sys.argv[2]
signature = sys.argv[3]

reader = PdfReader(input_file)
writer = PdfWriter()

packet = io.BytesIO()
c = canvas.Canvas(packet, pagesize=letter)

# Draw the signature in the bottom right corner
c.setFont("Helvetica-Bold", 14)
c.drawRightString(580, 40, signature)  # Adjust position as needed
c.save()

packet.seek(0)
signature_pdf = PdfReader(packet)
signature_page = signature_pdf.pages[0]

# Copy all pages
for i in range(len(reader.pages)):
    page = reader.pages[i]
    if i == len(reader.pages) - 1:
        page.merge_page(signature_page)
    writer.add_page(page)

with open(output_file, "wb") as f:
    writer.write(f)
