//
//  LogScreenViewController.m
//  smart_gp_ip
//
//  Created by User on 5/26/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "LogScreenViewController.h"
#import <MessageUI/MessageUI.h>

@implementation LogScreenViewController

@synthesize date, timeSpent, activityType, logTitle, description, lessonsLearnt;

- (void)mailComposeController:(MFMailComposeViewController*)controller  
          didFinishWithResult:(MFMailComposeResult)result 
                        error:(NSError*)error {
	if (result == MFMailComposeResultSent) {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Mail sent." 
														message:@"Sending the mail succeeded." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Failure." 
														message:@"Sending the mail failed for unknown reason, try again later." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
	[self dismissModalViewControllerAnimated:YES];
	//[self.navigationController popViewControllerAnimated:YES];
}
- (IBAction) sendAction: (id) sender {
	NSString* path = [self getFilePath];
	NSArray* arrayOfLogs = [NSArray arrayWithContentsOfFile:path];
	NSString* body = [NSString stringWithString:@""];
	body = [self prepareBody: body withItems: arrayOfLogs];
	MFMailComposeViewController* controller = [[MFMailComposeViewController alloc] init];
	controller.mailComposeDelegate = self;
	[controller setSubject:@"My Log"];
	[controller setMessageBody:body isHTML:NO]; 
	if (controller) [self presentModalViewController:controller animated:YES];
	[controller release];
}
- (NSString*) prepareBody: (NSString*) body withItems: (NSArray*) items {
	for (NSDictionary* dict in items) {
		for (NSString* key in [dict keyEnumerator]) {
			NSString* value = [dict objectForKey:key];
			body = [body stringByAppendingString:key];
			body = [body stringByAppendingString:@": "];
			body = [body stringByAppendingString:value];
			body = [body stringByAppendingString:@"\n"];
		}
		body = [body stringByAppendingString:@"\n"];
	}
	return body;
}
- (NSString*) getFilePath {
	NSArray* paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	return [[paths objectAtIndex:0] stringByAppendingPathComponent:@"log_data.plist"];
}
- (IBAction) saveAction: (id) sender {
	NSString* path = [self getFilePath];
	BOOL fileExists = [[NSFileManager defaultManager] fileExistsAtPath:path];
	if (!fileExists) {
		[[NSArray array] writeToFile:path atomically:YES];
	}
	fileExists = [[NSFileManager defaultManager] fileExistsAtPath:path];
	if (fileExists) {
		NSMutableArray* arrayOfLogs = [NSMutableArray arrayWithContentsOfFile:path];
		NSDictionary* dict = [NSDictionary dictionaryWithObjectsAndKeys:date.text, @"Date",
																		timeSpent.text, @"Time spent",
																		activityType.text, @"Activity type",
																		logTitle.text, @"Title",
																		description.text, @"Descritpion",
																		lessonsLearnt.text, @"Lesson learnt", nil];
		[arrayOfLogs addObject:dict];
		[arrayOfLogs writeToFile:path atomically:YES];
		arrayOfLogs = [NSMutableArray arrayWithContentsOfFile:path];
		if (arrayOfLogs) {
			UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Log created." message:@"Log creation succesful." delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
			[alert show];
			[alert release];
		}
	}
	[self.navigationController popViewControllerAnimated:YES];
}
- (void)viewDidLoad {
	date.delegate = self;
	timeSpent.delegate = self;
	activityType.delegate = self;
	logTitle.delegate = self;
	description.delegate = self;
	lessonsLearnt.delegate = self;
	self.title = @"New log";
	//set up date picking
    datePickerView = [[UIDatePicker alloc] init];
    [datePickerView sizeToFit];
    datePickerView.autoresizingMask = (UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleHeight);
	date.inputView = datePickerView;
	UIToolbar* keyboardDoneButtonView = [[UIToolbar alloc] init];
    keyboardDoneButtonView.barStyle = UIBarStyleBlack;
    keyboardDoneButtonView.translucent = YES;
    keyboardDoneButtonView.tintColor = nil;
    [keyboardDoneButtonView sizeToFit];
    UIBarButtonItem* doneButton = [[[UIBarButtonItem alloc] initWithTitle:@"Done"
																	style:UIBarButtonItemStyleBordered target:self
																   action:@selector(doneClickedDate:)] autorelease];
    [keyboardDoneButtonView setItems:[NSArray arrayWithObjects:doneButton, nil]];
	date.inputAccessoryView = keyboardDoneButtonView;
    [keyboardDoneButtonView release];
	
	// set up time spent picker
	timespentView = [[UIPickerView alloc] init]; 
	timespentView.delegate = self;
    [timespentView sizeToFit];
    timespentView.autoresizingMask = (UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleHeight);
    timespentView.showsSelectionIndicator = YES;
	
	timeSpent.inputView = timespentView; 
    UIToolbar* keyboardDoneButtonView2 = [[UIToolbar alloc] init];
    keyboardDoneButtonView2.barStyle = UIBarStyleBlack;
    keyboardDoneButtonView2.translucent = YES;
    keyboardDoneButtonView2.tintColor = nil;
    [keyboardDoneButtonView2 sizeToFit];
    UIBarButtonItem* doneButton2 = [[[UIBarButtonItem alloc] initWithTitle:@"Done"
																	 style:UIBarButtonItemStyleBordered target:self
																    action:@selector(doneClickedTime:)] autorelease];
    [keyboardDoneButtonView2 setItems:[NSArray arrayWithObjects:doneButton2, nil]];
	timeSpent.inputAccessoryView = keyboardDoneButtonView2;

	// set up time spent picker
	activityPickerView = [[UIPickerView alloc] init]; 
	activityPickerView.delegate = self;
    [activityPickerView sizeToFit];
    activityPickerView.autoresizingMask = (UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleHeight);
    activityPickerView.showsSelectionIndicator = YES;
	
	activityType.inputView = activityPickerView; 
    UIToolbar* keyboardDoneButtonView3 = [[UIToolbar alloc] init];
    keyboardDoneButtonView3.barStyle = UIBarStyleBlack;
    keyboardDoneButtonView3.translucent = YES;
    keyboardDoneButtonView3.tintColor = nil;
    [keyboardDoneButtonView3 sizeToFit];
    UIBarButtonItem* doneButton3 = [[[UIBarButtonItem alloc] initWithTitle:@"Done"
																	 style:UIBarButtonItemStyleBordered target:self
																    action:@selector(doneClickedActivity:)] autorelease];
    [keyboardDoneButtonView3 setItems:[NSArray arrayWithObjects:doneButton3, nil]];
	activityType.inputAccessoryView = keyboardDoneButtonView3;
	
	[super viewDidLoad];
}
- (void) doneClickedActivity: (id) sender {
	[activityPickerView resignFirstResponder];
	activityType.text = [self pickerView:activityPickerView titleForRow:activityRowSelection forComponent:0];
	[logTitle becomeFirstResponder];
}
- (void) doneClickedTime: (id) sender {
	[timeSpent resignFirstResponder];
	timeSpent.text = [self pickerView:timespentView titleForRow:timeSpentRowSelection forComponent:0];
	[activityType becomeFirstResponder];
}
- (void) doneClickedDate: (id) sender {
	[date resignFirstResponder];
	NSDate* datePicked = datePickerView.date;
	NSDateFormatter* frmt = [[NSDateFormatter alloc] init];
	[frmt setDateFormat:@"dd-MM-yyyy"];
	NSString* dateStr = [frmt stringFromDate:datePicked];
	date.text = dateStr;
	[frmt release];
	[timeSpent becomeFirstResponder];
}
-  (void) pickerView:(UIPickerView*) pickerView didSelectRow:(NSInteger) row inComponent:(NSInteger) component {
	if (pickerView == timespentView) timeSpentRowSelection = row;
	if (pickerView == activityPickerView) activityRowSelection = row;
}
- (NSString*) pickerView:(UIPickerView*) pickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component {
	if (pickerView == timespentView) {
		switch (row) {
			case 0: return [NSString stringWithString: @"1h"];
			case 1: return [NSString stringWithString: @"2h"];
			case 2: return [NSString stringWithString: @"3h"];
			case 3: return [NSString stringWithString: @"4h"];
			case 4: return [NSString stringWithString: @"5h"];
			case 5: return [NSString stringWithString: @"6h"];
			case 6: return [NSString stringWithString: @"7h"];
			case 7: return [NSString stringWithString: @"8h"];
		}
	}
	if (pickerView == activityPickerView) {
		switch (row) {
			case 0: return [NSString stringWithString: @"READING"];
			case 1: return [NSString stringWithString: @"LECTURE/MEETING"];
			case 2: return [NSString stringWithString: @"WEB BASED"];
			case 3: return [NSString stringWithString: @"PUNS/DENS"];
			case 4: return [NSString stringWithString: @"SIG EVENT"];
			case 5: return [NSString stringWithString: @"AUDIT"];
			case 6: return [NSString stringWithString: @"OTHER"];
		}
	}
	return @"";
}
- (NSInteger)numberOfComponentsInPickerView:(UIPickerView *)pickerView {
	return 1;
}
- (NSInteger)pickerView:(UIPickerView *)pickerView numberOfRowsInComponent:(NSInteger)component {
	if (pickerView == timespentView) {
		return 8;
	}
	if (pickerView == activityPickerView) {
		return 7;
	}
	return 0;
}
- (BOOL) textFieldShouldReturn:(UITextField *)textField {
	if (textField == date) {
		[timeSpent becomeFirstResponder];
	} else if (textField == timeSpent) {
		[activityType becomeFirstResponder];
	} else if (textField == activityType) {
		[logTitle becomeFirstResponder];
	} else if (textField == logTitle) {
		[description becomeFirstResponder];
	} else if (textField == description) {
		[lessonsLearnt becomeFirstResponder];
	} else if (textField == lessonsLearnt) {
		[lessonsLearnt resignFirstResponder];
	}
	return YES;
}
- (void)textFieldDidBeginEditing:(UITextField *)textField {
	if (textField == date || textField == timeSpent || textField == activityType) {
		return;
	}
    if (UI_USER_INTERFACE_IDIOM() != UIUserInterfaceIdiomPad) {
		[self animateTextField: textField up: YES];
	}	
}
- (void)textFieldDidEndEditing:(UITextField *)textField {
    if (textField == date || textField == timeSpent || textField == activityType) {
		return;
	}
	if (UI_USER_INTERFACE_IDIOM() != UIUserInterfaceIdiomPad) {
		[self animateTextField: textField up: NO];
	}
}

- (void) animateTextField: (UITextField*) textField up: (BOOL) up {
    const int movementDistance = 90; // tweak as needed
    const float movementDuration = 0.3f; // tweak as needed
    int movement = (up ? -movementDistance : movementDistance);
	
    [UIView beginAnimations: @"anim" context: nil];
    [UIView setAnimationBeginsFromCurrentState: YES];
    [UIView setAnimationDuration: movementDuration];
    self.view.frame = CGRectOffset(self.view.frame, 0, movement);
    [UIView commitAnimations];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)viewDidUnload {
    [super viewDidUnload];
}
- (void)dealloc {
	[date release];
	[timeSpent release];
	[activityType release];
	[logTitle release];
	[description release];
	[lessonsLearnt release];
	[datePickerView release];
	[timespentView release];
	[activityPickerView release];
    [super dealloc];
}
@end
