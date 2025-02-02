//
//  Vector3D_PS1.cpp
//  problem asm1
//
//  Created by Vu Duc Tran on 17/3/2024.
//
#define _USE_MATH_DEFINES     // must be defined before any #include
#include "Vector3D.h"
#include <cassert>
#include <cmath>
#include <sstream>
#include <iomanip>

using namespace std;


std::string Vector3D::toString() const noexcept
{
    std::stringstream ss;
        //Write
        ss << "[" << std::round(x() * 10000.0f) / 10000.0f << ","
                  << std::round(y() * 10000.0f) / 10000.0f << ","
                  << std::round(w() * 10000.0f) / 10000.0f << "]";
        //Return the resulting string
        return ss.str();
}

