package com.example.localclimbingscore

import android.graphics.Color
import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
class MainActivity : AppCompatActivity() {

    private lateinit var tv_score: TextView
    private lateinit var tv_hold: TextView
    private lateinit var b_climb: Button
    private lateinit var b_fall: Button
    private lateinit var b_reset: Button

    private var score = 0
    private var currentHold = 0
    private var hasFallen = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }
        tv_hold = findViewById(R.id.tv_hold)
        tv_score = findViewById(R.id.tv_score)
        b_climb = findViewById(R.id.b_climb)
        b_fall = findViewById(R.id.b_fall)
        b_reset = findViewById(R.id.b_reset)

        updateScore()

        b_climb.setOnClickListener {
            if (!hasFallen && currentHold < 9) {
                currentHold++
                score += getHoldScore(currentHold)
                score = minOf(score, 18)
                updateScore()
            }
        }

        b_fall.setOnClickListener {
            if (!hasFallen && currentHold >= 1) {
                hasFallen = true
                if (currentHold < 9) {
                    score = maxOf(score - 3, 0)
                }
                updateScore()
            }
        }

        b_reset.setOnClickListener {
            reset()
        }
    }

    private fun getHoldScore(hold: Int): Int {
        return when (hold) {
            in 1..3 -> 1
            in 4..6 -> 2
            in 7..9 -> 3
            else -> 0
        }
    }

    private fun updateScore() {
        tv_score.text = "SCORE: $score"
        tv_hold.text = "HOLDS: $currentHold"
        tv_score.setTextColor(getZoneColor(currentHold))

        if (currentHold == 9) {
            tv_score.text = "SCORE: $score You're the winner"
            tv_hold.text = "HOLDS: $currentHold!"

        } else if (hasFallen) {
            tv_score.text = "SCORE: $score You fell"
            tv_hold.text = "Your highest hold is $currentHold!"
        }
    }

    private fun getZoneColor(hold: Int): Int {
        return when (hold) {
            in 1..3 -> Color.BLUE
            in 4..6 -> Color.GREEN
            in 7..9 -> Color.RED
            else -> Color.WHITE
        }
    }

    private fun reset() {
        score = 0
        currentHold = 0
        hasFallen = false
        updateScore()
    }
}
