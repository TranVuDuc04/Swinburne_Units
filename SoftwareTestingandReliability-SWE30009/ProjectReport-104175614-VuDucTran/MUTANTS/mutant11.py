def is_leap(year):
    year -= 1
    leap = False
    if year % 4 == 0 and year % 100 != 0:
        leap = True
    elif year % 400 == 0:
        leap = True
    return leap

print(is_leap(int(input("Enter the year: "))))
