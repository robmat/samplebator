
//Copyright Applicable Ltd 2011

#import "LogListCell.h"
#import "LogScreenViewController.h"
#import "SetReminderViewController.h"

@implementation LogListCell

@synthesize titleLbl, detailLbl, log, navController, tvc, locNot;

- (IBAction) editAction: (id) sender {
	if (reminder) {
		NSString* submitLabelStr = locNot.alertBody;
		int index = 0;
		SetReminderViewController* srvc = [[SetReminderViewController alloc] initWithNibName:nil bundle:nil];
		if ([submitLabelStr rangeOfString:@"MEETING"].location != NSNotFound) {
			index = [submitLabelStr rangeOfString:@"MEETING"].length;
			srvc.title = @"Meeting reminder";
		}
		if ([submitLabelStr rangeOfString:@"PHONE CALL"].location != NSNotFound) {
			index = [submitLabelStr rangeOfString:@"PHONE CALL"].length;
			srvc.title = @"Phone call reminder";
		}
		if ([submitLabelStr rangeOfString:@"CHECK PATIENT"].location != NSNotFound) {
			index = [submitLabelStr rangeOfString:@"CHECK PATIENT"].length;
			srvc.title = @"Check patient reminder"; 
		}
		if ([submitLabelStr rangeOfString:@"CHECK RESULT"].location != NSNotFound) {
			index = [submitLabelStr rangeOfString:@"CHECK RESULT"].length;
			srvc.title = @"Check result reminder"; 
		}
		srvc.locNot = locNot;
		srvc.submitLabelText = [submitLabelStr substringToIndex:index];
		srvc.actionStr = [submitLabelStr substringFromIndex:index];
		[self.navController pushViewController:srvc animated:YES];
		[srvc release];
	} else {
		LogScreenViewController* llvc = [[LogScreenViewController alloc] initWithNibName:nil bundle:nil];
		llvc.log = log;
		[self.navController pushViewController:llvc animated:YES];
		[llvc release];
	}
}
- (IBAction) deleteAction: (id) sender {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Really delete?" 
													message:@"Are you sure?" 
												   delegate:self 
										  cancelButtonTitle:@"Cancel" 
										  otherButtonTitles:@"Yes", nil];
	[alert show];
	[alert release];
}
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
	if (buttonIndex == 1) {
		if (reminder) {
			[[UIApplication sharedApplication] cancelLocalNotification:locNot];
		} else {
			NSMutableArray* logs = [NSMutableArray arrayWithContentsOfFile:[LogScreenViewController getFilePath]];
			NSDictionary* logToRemove = nil;
			for (NSDictionary* logDict in logs) {
				NSNumber* time1 = [logDict objectForKey:@"Id"];
				NSNumber* time2 = [log objectForKey:@"Id"];
				if ([time1 isEqualToNumber:time2]) {
					logToRemove = logDict; 
				}
			}
			[logs removeObject:logToRemove];
			[logs writeToFile:[LogScreenViewController getFilePath] atomically:YES];
		}
		[tvc.tableView reloadData];
	}
}
- (void)dealloc {
	[titleLbl release];
	[detailLbl release];
	[log release];
	[locNot release];
	[tvc release];
	[super dealloc];
}

@end
