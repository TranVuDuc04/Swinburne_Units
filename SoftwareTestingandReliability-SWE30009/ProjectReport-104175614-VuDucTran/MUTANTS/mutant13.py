def is_leap(year):
    leap = False
    if year % 100 == 0 and (year % 400 == 0 or year == 1900):
        leap = True
    return leap

print(is_leap(int(input("Enter the year: "))))
