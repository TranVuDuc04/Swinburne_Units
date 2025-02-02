def is_leap(year):
    leap = not False
    if year % 4 == 0 and year % 100 != 0:
        leap = True
    elif year % 400 == 0:
        leap = True
    return not leap

print(is_leap(int(input("Enter the year: "))))
