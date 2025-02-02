package com.example.localclimbingscore

import android.graphics.Color
import android.os.Bundle
import android.util.Log
import android.widget.Button
import android.widget.ImageView
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.appcompat.app.AppCompatDelegate
import androidx.core.os.LocaleListCompat
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import java.util.*

class MainActivity : AppCompatActivity() {

    private lateinit var tvScore: TextView
    private lateinit var tvHold: TextView
    private lateinit var btnClimb: Button
    private lateinit var btnFall: Button
    private lateinit var btnReset: Button
    private lateinit var btnChangeLanguage: Button
    private lateinit var ivPicture: ImageView

    private var score = 0
    private var currentHold = 0
    private var hasFallen = false
    private var currentPictureIndex = 0

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        tvHold = findViewById(R.id.tv_hold)
        tvScore = findViewById(R.id.tv_score)
        btnClimb = findViewById(R.id.b_climb)
        btnFall = findViewById(R.id.b_fall)
        btnReset = findViewById(R.id.b_reset)
        btnChangeLanguage = findViewById(R.id.b_change_language)
        ivPicture = findViewById(R.id.iv_picture)

        if (savedInstanceState != null) {
            score = savedInstanceState.getInt("score")
            currentHold = savedInstanceState.getInt("currentHold")
            hasFallen = savedInstanceState.getBoolean("hasFallen")
            currentPictureIndex = savedInstanceState.getInt("currentPictureIndex")
            Log.d("Reload", currentPictureIndex.toString())
        }

        updateScore()
        updatePicture()

        //define button function
        btnClimb.setOnClickListener {
            if (!hasFallen && currentHold < 9) {
                currentHold++
                score += getHoldScore(currentHold)
                score = minOf(score, 18)
                currentPictureIndex = (currentPictureIndex + 1) % pictureResIds.size
                updateScore()
                updatePicture()
                Log.d("Add", "Here")
            }
        }

        btnFall.setOnClickListener {
            if (!hasFallen && currentHold >= 1) {
                hasFallen = true
                if (currentHold < 9) {
                    score = maxOf(score - 3, 0)
                }
                updateScore()
                updatePicture()
            }
        }

        btnReset.setOnClickListener {
            reset()
        }

        btnChangeLanguage.setOnClickListener {
            changeLanguage()
        }
    }

    //score to zone
    private fun getHoldScore(hold: Int): Int {
        return when (hold) {
            in 1..3 -> 1
            in 4..6 -> 2
            in 7..9 -> 3
            else -> 0
        }
    }

    private fun updateScore() {
        tvScore.text = getString(R.string.scores_text, score)
        tvHold.text = getString(R.string.holds_text, currentHold)
        tvScore.setTextColor(getZoneColor(currentHold))

        if (currentHold == 9) {
            tvScore.text = getString(R.string.winner_text, score)
            tvHold.text = getString(R.string.holds_text, currentHold)
            ivPicture.setImageResource(R.drawable.pic11)
        } else if (hasFallen) {
            tvScore.text = getString(R.string.fell_text, score)
            tvHold.text = getString(R.string.highest_hold_text, currentHold)
            ivPicture.setImageResource(R.drawable.pic10)
        }
    }

    //change color based on zone
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
        currentPictureIndex = 0
        Log.d("Reset", "Here")
        updateScore()
        updatePicture()
    }

    override fun onSaveInstanceState(outState: Bundle) {
        super.onSaveInstanceState(outState)
        outState.putInt("score", score)
        outState.putInt("currentHold", currentHold)
        outState.putBoolean("hasFallen", hasFallen)
        outState.putInt("currentPictureIndex", currentPictureIndex)
    }

    private fun changeLanguage() {
        val locale = if (resources.configuration.locales[0].language == "en") Locale("es") else Locale("en")
        val localeList = LocaleListCompat.create(locale)
        AppCompatDelegate.setApplicationLocales(localeList)
        Log.d("currentID before change", currentPictureIndex.toString())
        recreate()
        Log.d("currentID after change", currentPictureIndex.toString())
    }

    private val pictureResIds = arrayOf(
        R.drawable.pic1,
        R.drawable.pic2,
        R.drawable.pic3,
        R.drawable.pic4,
        R.drawable.pic5,
        R.drawable.pic6,
        R.drawable.pic7,
        R.drawable.pic8,
        R.drawable.pic9
    )

    private fun updatePicture() {
        if (!hasFallen && currentHold < 9) {
            Log.d("current ID", currentPictureIndex.toString())
            ivPicture.setImageResource(pictureResIds[currentPictureIndex])
        }
    }
}
