import segno

# Loop through each code and generate QR code
for i in range(1, 2):
    code = f"CRESH{i:03}"  # Format the code with leading zeros
    qrcode = segno.make_qr(code)
    qrcode.save(
        f"{code}.png",  # Save with the code as part of the filename
        scale=5,
        light="white",
        dark="green",
    )
