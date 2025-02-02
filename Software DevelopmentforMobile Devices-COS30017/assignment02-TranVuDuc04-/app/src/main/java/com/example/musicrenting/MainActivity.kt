package com.example.musicrenting

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Button
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.fragment.app.Fragment
import androidx.fragment.app.FragmentTransaction



class MainActivity : AppCompatActivity() {

    private var currentFragmentTag: String? = null
    private var savedOrderData: String? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        val textViewBorrow: TextView = findViewById(R.id.textViewBorrow)
        val textViewBooking: TextView = findViewById(R.id.textViewBooking)
        val buttonFragment1: Button = findViewById(R.id.buttonFragment1)
        val buttonFragment2: Button = findViewById(R.id.buttonFragment2)
        val buttonFragment3: Button = findViewById(R.id.buttonFragment3)
        val borrowButton: Button = findViewById(R.id.borrowButton)
        val fragmentContainer: View = findViewById(R.id.fragmentContainer)

        //restore saved state if available
        if (savedInstanceState != null) {
            currentFragmentTag = savedInstanceState.getString("CURRENT_FRAGMENT_TAG")
            savedOrderData = savedInstanceState.getString("SAVED_ORDER_DATA")

            currentFragmentTag?.let {
                val fragment = supportFragmentManager.findFragmentByTag(it)
                if (fragment != null) {
                    showFragment(fragment, it)
                }
            }
        } else {
            //only display Insu1 fragment if there's no saved instance
            showFragment(Insu1(), "Insu1")
        }

        textViewBorrow.setOnClickListener {
            fragmentContainer.visibility = View.VISIBLE
        }

        textViewBooking.setOnClickListener {
            if (savedOrderData != null) {
                val orderDetails = savedOrderData!!.split("\n")
                val instrumentName = orderDetails[0].substringAfter(": ").trim()
                val quantity = orderDetails[1].substringAfter(": ").trim().toInt()
                val condition = orderDetails[2].substringAfter(": ").trim()
                val totalPrice = orderDetails[3].substringAfter(": ").trim().substring(1).toFloat()

                val intent = Intent(this, PrintingReceiptActivity::class.java).apply {
                    putExtra("INSTRUMENT_NAME", instrumentName)
                    putExtra("QUANTITY", quantity)
                    putExtra("CONDITION", condition)
                    putExtra("TOTAL_PRICE", totalPrice)
                }
                startActivity(intent)
            } else {
                Toast.makeText(this, "No booking has been made yet.", Toast.LENGTH_SHORT).show()
            }
        }

        buttonFragment1.setOnClickListener { showFragment(Insu1(), "Insu1") }
        buttonFragment2.setOnClickListener { showFragment(Insu2(), "Insu2") }
        buttonFragment3.setOnClickListener { showFragment(Insu3(), "Insu3") }

        borrowButton.setOnClickListener {
            saveCurrentFragment()
        }
    }


    private fun showFragment(fragment: Fragment, tag: String) {
        supportFragmentManager.beginTransaction()
            .replace(R.id.fragmentContainer, fragment, tag)
            .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
            .commit()
        currentFragmentTag = tag
    }

    private fun saveCurrentFragment() {
        currentFragmentTag?.let { tag ->
            val fragment = supportFragmentManager.findFragmentByTag(tag)

            val instrument = when (fragment) {
                is Insu1 -> fragment.getInstrumentData()
                is Insu2 -> fragment.getInstrumentData()
                is Insu3 -> fragment.getInstrumentData()
                else -> null
            }

            instrument?.let {
                val intent = Intent(this, BookingActivity::class.java)
                intent.putExtra("INSTRUMENT_DATA", it)
                startActivityForResult(intent, 1) // Request code 1
            }

            val sharedPref = getSharedPreferences("fragmentState", MODE_PRIVATE)
            with(sharedPref.edit()) {
                putString("CURRENT_FRAGMENT_TAG", tag)
                apply()
            }
        }
    }

    //save the current fragment tag and saved order data when the screen rotates
    override fun onSaveInstanceState(outState: Bundle) {
        super.onSaveInstanceState(outState)
        outState.putString("CURRENT_FRAGMENT_TAG", currentFragmentTag)
        outState.putString("SAVED_ORDER_DATA", savedOrderData)
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)
        if (requestCode == 1 && resultCode == RESULT_OK && data != null) {
            //retrieve data from the result
            val instrumentName = data.getStringExtra("INSTRUMENT_NAME") ?: "N/A"
            val quantity = data.getIntExtra("QUANTITY", 0)
            val condition = data.getStringExtra("CONDITION") ?: "N/A"
            val totalPrice = data.getFloatExtra("TOTAL_PRICE", 0.0f)

            //format the saved order data
            savedOrderData = """
            Instrument: $instrumentName
            Quantity: $quantity
            Condition: $condition
            Total Price: $%.2f
        """.trimIndent().format(totalPrice)

        }
    }
}
