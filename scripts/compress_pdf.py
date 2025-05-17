import sys
import fitz  # PyMuPDF
from PIL import Image
import io

def compress_pdf(input_file, output_file, jpeg_quality=50):
    doc = fitz.open(input_file)
    total_images = 0
    replaced_images = 0
    skipped = 0

    for page_index, page in enumerate(doc):
        images = page.get_images(full=True)
        total_images += len(images)

        for img in images:
            xref = img[0]
            base_image = doc.extract_image(xref)
            image_bytes = base_image.get("image")
            image_ext = base_image.get("ext")

            if not image_bytes or not image_ext:
                skipped += 1
                continue

            try:
                pil_image = Image.open(io.BytesIO(image_bytes))
                pil_image = pil_image.convert("RGB")  # ensure JPEG-compatible

                img_io = io.BytesIO()
                pil_image.save(img_io, format="JPEG", quality=jpeg_quality)
                new_image_bytes = img_io.getvalue()

                # Replace image bytes directly in PDF object
                doc.update_image(xref, new_image_bytes)

                replaced_images += 1
            except Exception as e:
                skipped += 1
                continue

    print(f"Total images: {total_images}, replaced: {replaced_images}, skipped: {skipped}")

    doc.save(output_file, garbage=4, deflate=True, clean=True)
    doc.close()

if __name__ == "__main__":
    input_file = sys.argv[1]
    output_file = sys.argv[2]
    compress_pdf(input_file, output_file)


