import win32print

def get_installed_printers():
    printers = win32print.EnumPrinters(2)
    return printers

installed_printers = get_installed_printers()
for driver_name in installed_printers:
    print(driver_name)