import sys
from PyPDF2 import PdfReader, PdfWriter
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import letter
import io

input_file = sys.argv[1]
output_file = sys.argv[2]
watermark_text = sys.argv[3]

reader = PdfReader(input_file)
writer = PdfWriter()

# Create a watermark PDF in memory
packet = io.BytesIO()
c = canvas.Canvas(packet, pagesize=letter)
c.setFont("Helvetica", 40)
c.setFillGray(0.5, 0.5)  # light gray and transparent
c.saveState()
c.translate(300, 400)
c.rotate(45)
c.drawCentredString(0, 0, watermark_text)
c.restoreState()
c.save()

packet.seek(0)
watermark_pdf = PdfReader(packet)
watermark_page = watermark_pdf.pages[0]

# Overlay watermark on each page
for page in reader.pages:
    page.merge_page(watermark_page)
    writer.add_page(page)

with open(output_file, "wb") as f:
    writer.write(f)
