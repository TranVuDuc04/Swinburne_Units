package com.example.moneyshare

import androidx.test.ext.junit.runners.AndroidJUnit4
import androidx.test.core.app.ActivityScenario
import androidx.test.espresso.Espresso.onView
import androidx.test.espresso.action.ViewActions.click
import androidx.test.espresso.action.ViewActions.typeText
import androidx.test.espresso.assertion.ViewAssertions.matches
import androidx.test.espresso.matcher.ViewMatchers.isDisplayed
import androidx.test.espresso.matcher.ViewMatchers.withId
import androidx.test.espresso.matcher.ViewMatchers.withText
import com.example.moneyshare.activity.MainActivity
import org.junit.Test
import org.junit.runner.RunWith

@RunWith(AndroidJUnit4::class)
class AddEventTest {

    @Test
    fun testAddEventFunctionality() {
        //launch
        ActivityScenario.launch(MainActivity::class.java)

        //click the Add Event button
        onView(withId(R.id.btnAddEvent)).perform(click())

        //enter the event name and date
        onView(withId(R.id.etName)).perform(typeText("Melbourne"))
        onView(withId(R.id.etDate)).perform(typeText("New Event"))

        //click create
        onView(withId(R.id.btnCreate)).perform(click())

        //check if the event text is displayed
        onView(withText("New Event")).check(matches(isDisplayed()))
    }
}
