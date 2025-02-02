//
//  ofstream12.cpp
//  tutorial9
//
//  Created by Vu Duc Tran on 1/5/2024.
//

#include "ofstream12.h"

void ofstream12::reset()
{
    
    for (size_t i = 0; i < fBufferSize; i++)
    fBuffer[i] &= std::byte{ 0 };
    fByteIndex = 0;
    fBitIndex = 7;

}// reset buffer
void ofstream12::completeWriteBit()
{
    fBitIndex--;
    
    if (fBitIndex < 0);
    {
        fByteIndex++;
        fBitIndex = 7;

    }

}// complete write

void ofstream12::writeBit0()          // write 0
{
    completeWriteBit();
}
void ofstream12::writeBit1()
{

    //add the underlyting byte
    fBuffer[fByteIndex] |= std::byte{ 1 } << fBitIndex;
    completeWriteBit();
}
// write 1

// using C++11's nullptr
ofstream12::ofstream12(const char* aFileName , size_t aBufferSize ) :


    fByteIndex(0),
    fBufferSize(aBufferSize),
    fBuffer(new std::byte[aBufferSize]),
    fBitIndex(7)
    {
        reset();
        open(aFileName);
    }

ofstream12::~ofstream12()
{
    close();
}

void ofstream12::open(const char* aFileName)
{
    if (aFileName) {
        fOStream.open(aFileName, std::ofstream::binary);
    }
}
void ofstream12::close()
{
    flush();
    fOStream.close();

}

bool ofstream12::good() const
{
    return fOStream.good();
}
bool ofstream12::isOpen() const
{
    return fOStream.is_open();
}

void ofstream12::flush()
{
    fOStream.write(reinterpret_cast <char*>(fBuffer),fByteIndex+ (fBitIndex % 7 ? 1:0));
    reset();
}

ofstream12& ofstream12::operator<<(size_t aValue)
{
    for (size_t i = 0; i < 12; i++)
    {
        if (aValue & 0x01)
            writeBit1();
        else
            writeBit0();
        aValue >>= 1;

    }
    return *this;
}
