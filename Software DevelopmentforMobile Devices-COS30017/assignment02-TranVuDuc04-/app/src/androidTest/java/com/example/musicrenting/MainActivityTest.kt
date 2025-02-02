package com.example.musicrenting

import androidx.test.ext.junit.runners.AndroidJUnit4
import androidx.test.core.app.ActivityScenario
import androidx.test.espresso.Espresso.onView
import androidx.test.espresso.action.ViewActions.click
import androidx.test.espresso.assertion.ViewAssertions.matches
import androidx.test.espresso.matcher.ViewMatchers.isDisplayed
import androidx.test.espresso.matcher.ViewMatchers.withId
import org.junit.Test
import org.junit.runner.RunWith

@RunWith(AndroidJUnit4::class)
class MainActivityTest {
    @Test
    fun testBorrowButtonFunctionality() {
        //launch MainActivity
        ActivityScenario.launch(MainActivity::class.java)
        //click the Borrow button
        onView(withId(R.id.borrowButton)).perform(click())
        //check that the creditTextView is displayed in BookingActivity
        //if the creditTextView is displayed, that means borrow button navigates to BookingActivity
        onView(withId(R.id.creditTextView)).check(matches(isDisplayed()))
    }
}
