import sys
from PyPDF2 import PdfMerger

input1 = sys.argv[1]
input2 = sys.argv[2]
output = sys.argv[3]

merger = PdfMerger()
merger.append(input1)
merger.append(input2)
merger.write(output)
merger.close()
