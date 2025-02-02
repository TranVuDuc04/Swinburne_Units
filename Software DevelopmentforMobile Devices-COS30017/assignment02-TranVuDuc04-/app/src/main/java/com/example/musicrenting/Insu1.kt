package com.example.musicrenting

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.RadioGroup
import android.widget.SeekBar
import android.widget.TextView
import android.widget.RatingBar
import androidx.fragment.app.Fragment

class Insu1 : Fragment() {

    private lateinit var instrumentImage: ImageView
    private lateinit var radioGroup: RadioGroup
    private lateinit var quantityLabel: TextView
    private lateinit var priceTextView: TextView
    private lateinit var seekBar: SeekBar
    private lateinit var ratingBar: RatingBar
    private val pricePerItem = 50 //price for one guitar

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        return inflater.inflate(R.layout.fragment_insu1, container, false)
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        instrumentImage = view.findViewById(R.id.instrumentImage)
        radioGroup = view.findViewById(R.id.colorGroup)
        quantityLabel = view.findViewById(R.id.quantityLabel)
        priceTextView = view.findViewById(R.id.priceTextView)
        seekBar = view.findViewById(R.id.quantitySeekBar)
        ratingBar = view.findViewById(R.id.ratingBar)

        radioGroup.setOnCheckedChangeListener { _, checkedId ->
            when (checkedId) {
                R.id.colorBlack -> instrumentImage.setImageResource(R.drawable.g1)
                R.id.colorBlue -> instrumentImage.setImageResource(R.drawable.g2)
                R.id.colorCream -> instrumentImage.setImageResource(R.drawable.g3)
            }
        }

        seekBar.setOnSeekBarChangeListener(object : SeekBar.OnSeekBarChangeListener {
            override fun onProgressChanged(seekBar: SeekBar, progress: Int, fromUser: Boolean) {
                quantityLabel.text = "Quantity: $progress"
                updatePrice(progress) //update total price based on quantity
            }

            override fun onStartTrackingTouch(seekBar: SeekBar) {}
            override fun onStopTrackingTouch(seekBar: SeekBar) {}
        })
    }

    private fun updatePrice(quantity: Int) {
        val totalPrice = quantity * pricePerItem
        priceTextView.text = "Price: $$totalPrice"
    }

    fun getInstrumentData(): Instrument {
        val color = when (radioGroup.checkedRadioButtonId) {
            R.id.colorBlack -> "Black"
            R.id.colorBlue -> "Blue"
            R.id.colorCream -> "Cream"
            else -> "Unknown"
        }
        val quantity = seekBar.progress
        val pricePerMonth = pricePerItem
        val rating = ratingBar.rating

        return Instrument(
            name = "Guitar",
            color = color,
            rating = rating,
            isAvailable = true,
            pricePerMonth = pricePerMonth,
            quantity = quantity,
            type = "Guitar"
        )
    }

    fun isValid(): Boolean {
        return seekBar.progress > 0
    }
}
