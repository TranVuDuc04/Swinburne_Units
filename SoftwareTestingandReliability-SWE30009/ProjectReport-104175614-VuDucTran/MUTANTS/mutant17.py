def is_leap(year):
    leap = False
    if year > 1900 and year % 4 == 0 and year % 100 != 0:
        leap = True
    elif year > 1900 and year % 400 == 0:
        leap = True
    return leap

print(is_leap(int(input("Enter the year: "))))
