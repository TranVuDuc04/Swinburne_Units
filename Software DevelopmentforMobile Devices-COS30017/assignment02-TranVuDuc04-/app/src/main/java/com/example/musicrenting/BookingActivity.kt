
package com.example.musicrenting

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.*
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity

class BookingActivity : AppCompatActivity() {

    private lateinit var quantitySeekBar: SeekBar
    private lateinit var priceTextView: TextView
    private lateinit var instrument: Instrument
    private lateinit var quantityLabel: TextView
    private lateinit var conditionRadioGroup: RadioGroup
    private lateinit var saveButton: Button

    private var availableCredit = 500f
    private var currentTotalPrice = 0f // Store the total price
    private var currentQuantity = 1 // Store the quantity
    private var currentConditionMultiplier = 1.0f // Store condition multiplier

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_booking)

        // Initialize UI elements
        val instrumentDetails = findViewById<TextView>(R.id.instrumentDetails)
        val instrumentImage = findViewById<ImageView>(R.id.instrumentImage)
        priceTextView = findViewById(R.id.priceTextView)
        quantitySeekBar = findViewById(R.id.quantitySeekBar)
        quantityLabel = findViewById(R.id.quantityLabel)
        conditionRadioGroup = findViewById(R.id.conditionRadioGroup)
        saveButton = findViewById(R.id.saveButton)

        instrument = intent.getParcelableExtra("INSTRUMENT_DATA") ?: return

        // Set instrument details
        instrumentDetails.text = """
            Name: ${instrument.name}
            Color: ${instrument.color}
            Rating: ${instrument.rating}
        """.trimIndent()

        instrumentImage.setImageResource(getImageResource(instrument.type, instrument.color))

        currentQuantity = savedInstanceState?.getInt("CURRENT_QUANTITY", instrument.quantity) ?: instrument.quantity
        currentTotalPrice = savedInstanceState?.getFloat("CURRENT_TOTAL_PRICE", 0f) ?: currentTotalPrice
        currentConditionMultiplier = savedInstanceState?.getFloat("CURRENT_CONDITION_MULTIPLIER", 1.0f) ?: 1.0f

        updatePrice(currentQuantity)
        quantityLabel.text = "Quantity: $currentQuantity"

        quantitySeekBar.progress = currentQuantity
        quantitySeekBar.setOnSeekBarChangeListener(object : SeekBar.OnSeekBarChangeListener {
            override fun onProgressChanged(seekBar: SeekBar?, progress: Int, fromUser: Boolean) {
                quantityLabel.text = "Quantity: $progress"
                updatePrice(progress)
            }

            override fun onStartTrackingTouch(seekBar: SeekBar?) {}
            override fun onStopTrackingTouch(seekBar: SeekBar?) {}
        })

        conditionRadioGroup.setOnCheckedChangeListener { _, _ ->
            updatePrice(quantitySeekBar.progress)
        }

        saveButton.setOnClickListener {
            //show confirmation dialog before saving
            AlertDialog.Builder(this)
                .setTitle("Confirm Order")
                .setMessage("Do you want to confirm the order?")
                .setPositiveButton("Yes") { dialog, _ ->
                    dialog.dismiss()
                    validateAndSave() //proceed with saving if confirmed
                }
                .setNegativeButton("No") { dialog, _ ->
                    dialog.dismiss()
                }
                .show()
        }

    }

    private fun updatePrice(quantity: Int) {
        val basePrice = instrument.pricePerMonth
        Log.d("test", conditionRadioGroup.checkedRadioButtonId.toString())
        currentConditionMultiplier = when (conditionRadioGroup.checkedRadioButtonId) {
            R.id.radioLikeNew -> 0.8f
            else -> 1.0f
        }
        currentTotalPrice = quantity * basePrice * currentConditionMultiplier
        priceTextView.text = "Price: $%.2f".format(currentTotalPrice)
    }

    private fun validateAndSave() {
        val quantity = quantitySeekBar.progress
        val totalPrice = currentTotalPrice

        when {
            quantity <= 0 -> {
                Toast.makeText(this, "Quantity must be greater than 0", Toast.LENGTH_SHORT).show()
                return
            }
            conditionRadioGroup.checkedRadioButtonId == -1 -> {
                Toast.makeText(this, "Please select a condition", Toast.LENGTH_SHORT).show()
                return
            }
            totalPrice > availableCredit -> {
                Toast.makeText(this, "Total price exceeds available credit", Toast.LENGTH_SHORT).show()
                return
            }
        }

        Toast.makeText(this, "Booking saved. Total Price: $%.2f".format(totalPrice), Toast.LENGTH_SHORT).show()

        // Return data to MainActivity
        val resultIntent = Intent().apply {
            putExtra("INSTRUMENT_NAME", instrument.name)
            putExtra("QUANTITY", quantity)
            putExtra("CONDITION", if (currentConditionMultiplier == 0.8f) "Like New" else "New")
            putExtra("TOTAL_PRICE", totalPrice)
        }
        setResult(RESULT_OK, resultIntent)
        finish()
    }

    override fun onSaveInstanceState(outState: Bundle) {
        super.onSaveInstanceState(outState)
        outState.putInt("CURRENT_QUANTITY", quantitySeekBar.progress)
        outState.putFloat("CURRENT_TOTAL_PRICE", currentTotalPrice)
        outState.putFloat("CURRENT_CONDITION_MULTIPLIER", currentConditionMultiplier)
    }

    override fun onBackPressed() {
        AlertDialog.Builder(this)
            .setTitle("Cancel Order")
            .setMessage("Do you want to cancel the order?")
            .setPositiveButton("Yes") { dialog, _ ->
                dialog.dismiss()
                super.onBackPressed()
            }
            .setNegativeButton("No") { dialog, _ ->
                dialog.dismiss()
            }
            .show()
    }

    private fun getImageResource(type: String, color: String): Int {
        return when (type) {
            "Guitar" -> when (color) {
                "Black" -> R.drawable.g1
                "Blue" -> R.drawable.g2
                "Cream" -> R.drawable.g3
                else -> R.drawable.g1
            }
            "Piano" -> when (color) {
                "Black" -> R.drawable.p1
                "White" -> R.drawable.p2
                "Brown" -> R.drawable.p3
                else -> R.drawable.p1
            }
            "Trumpet" -> when (color) {
                "Black" -> R.drawable.t1
                "Blue" -> R.drawable.t2
                "Gold" -> R.drawable.t3
                else -> R.drawable.t1
            }
            else -> R.drawable.g1
        }
    }
}