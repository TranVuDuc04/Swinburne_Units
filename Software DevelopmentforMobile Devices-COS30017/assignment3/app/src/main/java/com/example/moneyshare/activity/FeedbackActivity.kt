package com.example.moneyshare.activity

import android.os.Bundle
import android.widget.Button
import android.widget.ImageView
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.example.moneyshare.R

class FeedbackActivity : AppCompatActivity() {

    private lateinit var ivEmoji: ImageView
    private lateinit var tvExperience: TextView
    private lateinit var tvFunctionRating: TextView
    private lateinit var tvDesignRating: TextView
    private lateinit var btnBack: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_feedback) // set activity layout

        // initialize views
        ivEmoji = findViewById(R.id.ivEmoji)
        tvExperience = findViewById(R.id.tvExperience)
        tvFunctionRating = findViewById(R.id.tvFunctionRating)
        tvDesignRating = findViewById(R.id.tvDesignRating)
        btnBack = findViewById(R.id.btnBack)

        // retrieve data from shared preferences
        val sharedPreferences = getSharedPreferences("FeedbackPrefs", MODE_PRIVATE)
        val experience = sharedPreferences.getString("EXPERIENCE", "No feedback given") ?: "No feedback given"
        val functionQuality = sharedPreferences.getFloat("FUNCTION_QUALITY", 0.0f)
        val designRating = sharedPreferences.getInt("DESIGN_RATING", 0)

        // set data to views
        tvExperience.text = "Experience: $experience"
        tvFunctionRating.text = "Function Quality: $functionQuality"
        tvDesignRating.text = "Design Rating: $designRating out of 10"

        // set emoji
        ivEmoji.setImageResource(if (experience == "Good") R.drawable.happy_emoji else R.drawable.sad_emoji)
        btnBack.setOnClickListener {
            finish()
        }
    }
}
