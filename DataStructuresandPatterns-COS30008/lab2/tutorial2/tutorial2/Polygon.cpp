//
//  Polygon.cpp
//  tutorial2 week02
//104175614
//  Created by Dylan Tran on 6/3/2024.
//
#include "Polygon.h"
#include <cassert>

// ':'== scope identifier
// init the members, create constructor

    
Polygon::Polygon() noexcept :
fNumberOfVertices()
{}

void Polygon::readData( std::istream& aIStream )
{
    // read data input from data.txt
    // detect number of elements in the data file
    
while ( aIStream >> fVertices[fNumberOfVertices]) //read the data into the array
    {
        fNumberOfVertices ++;
    }
}

size_t Polygon::getNumberOfVertices() const noexcept
{
    return fNumberOfVertices;
}

const Vector2D& Polygon::getVertex( size_t aIndex ) const
{
    assert(aIndex < fNumberOfVertices);
    
    return fVertices[aIndex];
}

float Polygon::getPerimeter() const noexcept
{
    float Result = 0.0f; //value: double
    
    if (fNumberOfVertices > 2)
        
    {
        for ( size_t i = 1;i < fNumberOfVertices; i++)
        {
            Result += (fVertices[i] - fVertices[i - 1]).length();
        }
        
        Result += (fVertices[0] - fVertices[fNumberOfVertices -1]).length();
    }
    
    
    
    return Result;
}
    
Polygon Polygon::scale( float aScalar ) const noexcept
{
    Polygon Result = *this; //reference; c++ idiom
    
    for ( size_t i = 1;i < fNumberOfVertices; i++)
    {
        Result.fVertices[i] = fVertices[i]*aScalar;
    }
    
    return Result;
}

