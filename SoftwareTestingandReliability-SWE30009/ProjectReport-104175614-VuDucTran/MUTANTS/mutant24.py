def is_leap(year):
    leap = False
    if year % 100 == 0:
        leap = True if year % 400 == 0 else False
    return leap

print(is_leap(int(input("Enter the year: "))))
