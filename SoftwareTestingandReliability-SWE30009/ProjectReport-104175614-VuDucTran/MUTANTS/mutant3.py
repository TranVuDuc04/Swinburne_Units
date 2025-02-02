def is_leap(year):
    if year % 4 == 0 and year % 100 != 0:
        return True
    elif year % 400 == 0:
        return True
    return False

print(is_leap(int(input("Enter the year: "))))
