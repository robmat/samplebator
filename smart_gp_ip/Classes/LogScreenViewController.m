#import "LogScreenViewController.h"
#import "LogsListViewController.h"
#import "EGOTextFieldAlertView.h"
#import "TextInputViewController.h"

@implementation LogScreenViewController

@synthesize date, timeSpent, activityType, logTitle, description, lessonsLearnt, log;

- (IBAction) viewLogsAction: (id) sender {
	LogsListViewController* llvc = [[LogsListViewController alloc] initWithNibName:nil bundle:nil];
	[self.navigationController pushViewController:llvc animated:YES];
	[llvc release];
}
+ (NSString*) getFilePath {
	NSArray* paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	return [[paths objectAtIndex:0] stringByAppendingPathComponent:@"log_data.plist"];
}
- (IBAction) saveAction: (id) sender {
	NSString* path = [LogScreenViewController getFilePath];
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
																		lessonsLearnt.text, @"Lesson learnt",
																		[NSNumber numberWithDouble:[[NSDate date] timeIntervalSince1970]], @"Id", nil];
		if (log != nil) {
			for (NSDictionary* logDict in arrayOfLogs) {
				NSNumber* time1 = [log objectForKey:@"Id"];
				NSNumber* time2 = [logDict objectForKey:@"Id"];
				if ([time1 isEqualToNumber:time2]) {
					NSUInteger index = [arrayOfLogs indexOfObject:logDict];
					[arrayOfLogs removeObjectAtIndex:index];
					[arrayOfLogs insertObject:dict atIndex:index];
				}
			}
		} else {
			[arrayOfLogs addObject:dict];
		}
		[arrayOfLogs writeToFile:path atomically:YES];
		arrayOfLogs = [NSMutableArray arrayWithContentsOfFile:path];
		if (arrayOfLogs) {
			UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Log operation." message:@"Log operation succesful." delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
			[alert show];
			[alert release];
		}
	}
	[self.navigationController popViewControllerAnimated:YES];
}
- (void)viewDidLoad {
	[super viewDidLoad];
	self.title = @"New log";
	if (log != nil) {
		date.text = [log objectForKey:@"Date"];
		timeSpent.text = [log objectForKey:@"Time spent"];
		activityType.text = [log objectForKey:@"Activity type"];
		logTitle.text = [log objectForKey:@"Title"];
		description.text = [log objectForKey:@"Descritpion"];
		lessonsLearnt.text = [log objectForKey:@"Lesson learnt"];
		self.title = @"Edit log";
	}
	date.delegate = self;
	timeSpent.delegate = self;
	activityType.delegate = self;
	logTitle.delegate = self;
	description.delegate = self;
	lessonsLearnt.delegate = self;
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
}
- (void) doneClickedActivity: (id) sender {
	[activityType resignFirstResponder];
	activityType.text = [self pickerView:activityPickerView titleForRow:activityRowSelection forComponent:0];
	//[logTitle becomeFirstResponder];
}
- (void) doneClickedTime: (id) sender {
	[timeSpent resignFirstResponder];
	timeSpent.text = [self pickerView:timespentView titleForRow:timeSpentRowSelection forComponent:0];
	//[activityType becomeFirstResponder];
}
- (void) doneClickedDate: (id) sender {
	[date resignFirstResponder];
	NSDate* datePicked = datePickerView.date;
	NSDateFormatter* frmt = [[NSDateFormatter alloc] init];
	[frmt setDateFormat:@"dd-MM-yyyy"];
	NSString* dateStr = [frmt stringFromDate:datePicked];
	date.text = dateStr;
	[frmt release];
	//[timeSpent becomeFirstResponder];
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
	[textField resignFirstResponder];
	/*if (textField == date) {
		[timeSpent becomeFirstResponder];
	} else if (textField == timeSpent) {
		[activityType becomeFirstResponder];
	} else if (textField == activityType) {
		[logTitle becomeFirstResponder];
	} else if (textField == logTitle) {
		[self descrptionAction:nil];
	} else if (textField == description) {
		[self lessonsLearntAction:nil];
	} */
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
- (IBAction) lessonsLearntAction: (id) sender {
	TextInputViewController* tivc = [[TextInputViewController alloc] initWithNibName:nil bundle:nil];
	tivc.title = @"Lessons learnt";
	tivc.targetTextView = lessonsLearnt;
	[self.navigationController pushViewController:tivc animated:YES];
	tivc.textView.text = lessonsLearnt.text;
	[tivc release];
}
- (IBAction) descrptionAction: (id) sender {
	TextInputViewController* tivc = [[TextInputViewController alloc] initWithNibName:nil bundle:nil];
	tivc.title = @"Description";
	tivc.targetTextView = description;
	[self.navigationController pushViewController:tivc animated:YES];
	tivc.textView.text = description.text;
	[tivc release];
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
