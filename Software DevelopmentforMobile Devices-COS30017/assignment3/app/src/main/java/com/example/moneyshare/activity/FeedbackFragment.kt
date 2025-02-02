package com.example.moneyshare.activity

import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import androidx.fragment.app.DialogFragment
import com.example.moneyshare.R

class FeedbackFragment : DialogFragment() {

    private lateinit var ivEmoji: ImageView
    private lateinit var radioGroupExperience: RadioGroup
    private lateinit var ratingBarFunction: RatingBar
    private lateinit var seekBarDesign: SeekBar
    private lateinit var tvFunctionQuality: TextView
    private lateinit var tvDesignRating: TextView

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        return inflater.inflate(R.layout.fragment_feedback, container, false)
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        // initialize views
        ivEmoji = view.findViewById(R.id.ivEmoji)
        radioGroupExperience = view.findViewById(R.id.radioGroupExperience)
        ratingBarFunction = view.findViewById(R.id.ratingBarFunction)
        seekBarDesign = view.findViewById(R.id.seekBarDesign)
        tvFunctionQuality = view.findViewById(R.id.tvFunctionQuality) // updated ID
        tvDesignRating = view.findViewById(R.id.tvDesignRating)

        // set initial function quality text
        updateFunctionQualityText()
        // radio group
        radioGroupExperience.setOnCheckedChangeListener { _, checkedId ->
            when (checkedId) {
                R.id.rbGood -> ivEmoji.setImageResource(R.drawable.happy_emoji) // good experience
                R.id.rbBad -> ivEmoji.setImageResource(R.drawable.sad_emoji) // bad experience
            }
        }
        // rating bar
        ratingBarFunction.setOnRatingBarChangeListener { _, rating, _ ->
            updateFunctionQualityText(rating)
        }

        // seek bar
        seekBarDesign.setOnSeekBarChangeListener(object : SeekBar.OnSeekBarChangeListener {
            override fun onProgressChanged(seekBar: SeekBar?, progress: Int, fromUser: Boolean) {
                tvDesignRating.text = "Design Rating: $progress out of 10" // update design rating
            }

            override fun onStartTrackingTouch(seekBar: SeekBar?) {}

            override fun onStopTrackingTouch(seekBar: SeekBar?) {}
        })

        // submit button
        view.findViewById<Button>(R.id.btnSubmitFeedback).setOnClickListener {
            submitFeedback()
        }
    }

    // updates function quality text
    private fun updateFunctionQualityText(rating: Float = ratingBarFunction.rating) {
        tvFunctionQuality.text = "Function Quality: $rating"
    }

    private fun submitFeedback() {
        val experience = when (radioGroupExperience.checkedRadioButtonId) {
            R.id.rbGood -> "Good"
            R.id.rbBad -> "Bad"
            else -> "No feedback given"
        }
        val functionQuality = ratingBarFunction.rating
        val designRating = seekBarDesign.progress

        val sharedPreferences = activity?.getSharedPreferences("FeedbackPrefs", AppCompatActivity.MODE_PRIVATE)
        sharedPreferences?.edit()?.apply {
            putString("EXPERIENCE", experience)
            putFloat("FUNCTION_QUALITY", functionQuality)
            putInt("DESIGN_RATING", designRating)
            apply()
        }
        dismiss()
    }
}
