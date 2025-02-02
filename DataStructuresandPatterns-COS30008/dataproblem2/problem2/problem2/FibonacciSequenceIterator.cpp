//
//  FibonacciSequenceIterator.cpp
//  problem2
//
//  Created by Vu Duc Tran on 21/4/2024.
//

#include <cassert>
#include "FibonacciSequenceIterator.h"

FibonacciSequenceIterator::FibonacciSequenceIterator(const FibonacciSequenceGenerator& aSequenceObject, long long aStart) noexcept : fSequenceObject(aSequenceObject), fIndex(aStart - 1) {}

const long long& FibonacciSequenceIterator::operator*() const noexcept {
    return *fSequenceObject; // Return the current Fibonacci number
}

FibonacciSequenceIterator& FibonacciSequenceIterator::operator++() noexcept {
    if (!fSequenceObject.hasNext()) {
        fIndex = -1;
    }
    else {
        ++fIndex;
        fSequenceObject.next(); 
    }
    return *this;
}

FibonacciSequenceIterator FibonacciSequenceIterator::operator++(int) noexcept {
    FibonacciSequenceIterator old = *this;
    ++(old);
    return old;
}

bool FibonacciSequenceIterator::operator==(const FibonacciSequenceIterator& aOther) const noexcept {
    return fSequenceObject.id() == aOther.fSequenceObject.id() && fIndex == aOther.fIndex;
}

bool FibonacciSequenceIterator::operator!=(const FibonacciSequenceIterator& aOther) const noexcept {
    return !(*this == aOther);
}
// return new iterator positioned at start
FibonacciSequenceIterator FibonacciSequenceIterator::begin() const noexcept {
    return FibonacciSequenceIterator(fSequenceObject);
}

// return new iterator positioned at limit
FibonacciSequenceIterator FibonacciSequenceIterator::end() const noexcept {
    // Return an iterator at the end position
    return FibonacciSequenceIterator(FibonacciSequenceGenerator(fSequenceObject.id()), 0);
}


