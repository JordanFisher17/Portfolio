package com.example.cs50project

import android.content.ContentValues
import android.content.Context
import android.database.sqlite.SQLiteDatabase
import android.database.sqlite.SQLiteOpenHelper

val DATABASE_NAME = "USERSDB"
val TABLE_NAME = "users"
val COL_ID = "ID"
val COL_USERNAME = "Username"
val COL_PASSWORD = "Password"
val COL_NOTES = "Notes"

class dbHelper (context: Context) : SQLiteOpenHelper(context, DATABASE_NAME, null, 1) {
    override fun onCreate(db: SQLiteDatabase?) {
        val CreateTable = ("CREATE TABLE " + TABLE_NAME + " ("
        + COL_ID + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
                COL_USERNAME + " TEXT UNIQUE, " +
                COL_PASSWORD + " TEXT, " +
                COL_NOTES + " TEXT " + ")" )

        db?.execSQL(CreateTable)
    }

    override fun onUpgrade(db: SQLiteDatabase?, p1: Int, p2: Int) {
        db?.execSQL("DROP TABLE IF EXISTS" + TABLE_NAME)
        onCreate(db)
    }

    //Function to add a new user
    fun AddUser (username : String, password : String, notes : String) {
        val values = ContentValues()

        values.put(COL_USERNAME, username.toString())
        values.put(COL_PASSWORD, password.toString())
        values.put(COL_NOTES, notes.toString())

        val db = this.writableDatabase

        db.insert(TABLE_NAME, null, values)

    }

    fun AddNotes (username : String, notes : String) {
        val values = ContentValues()
        val db = this.writableDatabase

        values.put(COL_NOTES, notes.toString())
        db.update(TABLE_NAME, values, "username = ?", arrayOf(username))

    }
}