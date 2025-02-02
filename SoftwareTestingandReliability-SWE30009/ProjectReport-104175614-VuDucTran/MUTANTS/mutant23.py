def is_leap(year):
    leap = False
    if year < 2000 or (year >= 2000 and year % 4 == 0 and year % 100 != 0):
        leap = True
    return leap

print(is_leap(int(input("Enter the year: "))))
