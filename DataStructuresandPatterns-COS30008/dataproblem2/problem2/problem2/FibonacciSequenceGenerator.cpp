//
//  FibonacciSequenceGenerator.cpp
//  problem2
//
//  Created by Vu Duc Tran on 21/4/2024.
//
#include <cassert>
#include "FibonacciSequenceGenerator.h"
using namespace std;
FibonacciSequenceGenerator::FibonacciSequenceGenerator(const std::string& aID) noexcept : fID(aID), fPrevious(0), fCurrent(1) {}

// Get sequence ID
const std::string& FibonacciSequenceGenerator::id() const noexcept {
    return fID;
}

// Get current Fibonacci number
const long long& FibonacciSequenceGenerator::operator*() const noexcept {
    return fCurrent;
}

// Type conversion to bool
FibonacciSequenceGenerator::operator bool() const noexcept {
    return hasNext();
}

// Reset sequence generator to first Fibonacci number
void FibonacciSequenceGenerator::reset() noexcept {
    fPrevious = 0;
    fCurrent = 1;
}

// Tests if there is a next Fibonacci number.
// Technically, there are infinitely many Fibonacci numbers,
// but the underlying integer data type limits the sequence.
bool FibonacciSequenceGenerator::hasNext() const noexcept {
    // Check if the next Fibonacci number is representable
    return fCurrent + fPrevious >= fCurrent;
}

// Advance to next Fibonacci number
// Function performs overflow assertion check.
void FibonacciSequenceGenerator::next() noexcept {
    assert(fCurrent >= 0 && "Overflow condition reached");
    long long nextFibonacci = fPrevious + fCurrent;
    fPrevious = fCurrent;
    fCurrent = nextFibonacci;
}
