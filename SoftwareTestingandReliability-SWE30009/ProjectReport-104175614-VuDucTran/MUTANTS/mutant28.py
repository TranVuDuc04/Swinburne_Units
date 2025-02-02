def is_leap(year):
    if year % 4 == 0 and year % 100 != 0:
        return year + 1
    elif year % 400 == 0:
        return year + 1
    return year

print(is_leap(int(input("Enter the year: "))))
