#include <iostream>
#include "Vector2D.hpp"
#include "Particle2D.hpp"

using namespace std;

int main()
{
    cout << "a similator\n" << endl;
    
    Particle2D obj( 0.0f,
                   10.0f,
                   Vector2D( 10.0f, 20.0f ),
                   Vector2D( 10.0f, 20.0f ),
                   Vector2D( 10.0f, 20.0f )
                   );
    
    do
    {
        cout << obj << endl;
        
        obj.updated();
    } while ( obj.getPosition().getY() >= 20.0f );
    
    cout << obj << endl;
    return 0;
}
