//
//  KeyProvider.cpp
//  midsem
//
//  Created by Vu Duc Tran on 24/4/2024.
//

#include "KeyProvider.h"
#include <cctype>
#include <cassert>

std::string KeyProvider::preprocessString(const std::string& aString) noexcept {
    std::string result;
    for (char c : aString) {
        if (std::isalpha(c)) {
            result += std::toupper(c);
        }
    }
    return result;
}

KeyProvider::KeyProvider(const std::string& aKeyword, const std::string& aSource) noexcept :
    fKeys(preprocessString(aKeyword)), fIndex(0) {
    size_t originalLength = fKeys.length();
    while (originalLength < aSource.length()) {
        fKeys += fKeys;
        originalLength += fKeys.length();
    }
    fKeys = fKeys.substr(0, preprocessString(aSource).length()); //substring
    assert(fKeys.length() <= preprocessString(aSource).length() && "The size of fKeys should match the size of the preprocessed input string.");
}

char KeyProvider::operator*() const noexcept {
    return fKeys[fIndex];
}

KeyProvider& KeyProvider::operator++() noexcept {
    ++fIndex;
    return *this;
}

KeyProvider KeyProvider::operator++(int) noexcept {
    KeyProvider temp = *this;
    ++(*this);
    return temp;
}

bool KeyProvider::operator==(const KeyProvider& aOther) const noexcept {
    return fKeys == aOther.fKeys && fIndex == aOther.fIndex;
}

bool KeyProvider::operator!=(const KeyProvider& aOther) const noexcept {
    return !(*this == aOther);
}

KeyProvider KeyProvider::begin() const noexcept {
    return *this;
}

KeyProvider KeyProvider::end() const noexcept {
    KeyProvider temp = *this;
    temp.fIndex = fKeys.length(); // Position after the last keyword character
    return temp;
}
