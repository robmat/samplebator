#import "RemindersMainMenuViewController.h"
#import "SetReminderViewController.h"

@implementation RemindersMainMenuViewController

- (IBAction) mettingAction: (id) sender {
	SetReminderViewController* srvc = [[SetReminderViewController alloc] initWithNibName:nil bundle:nil];
	srvc.title = @"Meeting reminder"; 
	srvc.submitLabelText = [NSString stringWithString: @"MEETING"];
	[self.navigationController pushViewController:srvc animated:YES];
	[srvc release];
}
- (IBAction) phoneCallAction: (id) sender {
}
- (IBAction) checkPatientAction: (id) sender {
}
- (IBAction) checkResultsAction: (id) sender {
}
- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = @"Reminders";
}
- (void)didReceiveMemoryWarning {
	[super didReceiveMemoryWarning];
}
- (void)viewDidUnload {
    [super viewDidUnload];
}
- (void)dealloc {
    [super dealloc];
}


@end
