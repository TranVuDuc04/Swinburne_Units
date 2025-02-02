package com.example.musicrenting

import android.view.View
import android.widget.SeekBar
import androidx.test.core.app.ActivityScenario
import androidx.test.espresso.Espresso.onView
import androidx.test.espresso.action.ViewActions.click
import androidx.test.espresso.assertion.ViewAssertions.matches
import androidx.test.espresso.matcher.ViewMatchers.withId
import androidx.test.espresso.matcher.ViewMatchers.withText
import androidx.test.espresso.ViewAction
import org.hamcrest.Matcher
import androidx.test.espresso.UiController
import org.junit.Test
import org.junit.runner.RunWith
import androidx.test.ext.junit.runners.AndroidJUnit4

@RunWith(AndroidJUnit4::class)
class ParcelableTest {
    @Test
    fun testParcelableDataTransfer() {
        ActivityScenario.launch(MainActivity::class.java)
        //select instrument type
        onView(withId(R.id.buttonFragment1)).perform(click())
        //set quantity to 5
        onView(withId(R.id.quantitySeekBar)).perform(setProgress(5))
        //click borrow button
        onView(withId(R.id.borrowButton)).perform(click())
        //check that the quantity passed to BookingActivity is displayed correctly
        onView(withId(R.id.quantityLabel))
            .check(matches(withText("Quantity: 5")))
    }


















    // Function to set progress on the SeekBar
    fun setProgress(progress: Int): ViewAction {
        return object : ViewAction {
            override fun getConstraints(): Matcher<View> {
                return withId(R.id.quantitySeekBar)
            }
            override fun getDescription(): String {
                return "Set progress on SeekBar to $progress"
            }
            override fun perform(uiController: UiController, view: View) {
                val seekBar = view as SeekBar
                seekBar.progress = progress
                seekBar.jumpDrawablesToCurrentState()
            }
        }
    }
}
