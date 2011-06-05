#import "RemindersMainMenuViewController.h"
#import "SetReminderViewController.h"
#import "RemindersListViewController.h"

@implementation RemindersMainMenuViewController

- (IBAction) mettingAction: (id) sender {
	SetReminderViewController* srvc = [[SetReminderViewController alloc] initWithNibName:nil bundle:nil];
	srvc.title = @"Meeting reminder"; 
	srvc.submitLabelText = [NSString stringWithString: @"MEETING"];
	[self.navigationController pushViewController:srvc animated:YES];
	[srvc release];
}
- (IBAction) phoneCallAction: (id) sender {
	SetReminderViewController* srvc = [[SetReminderViewController alloc] initWithNibName:nil bundle:nil];
	srvc.title = @"Phone call reminder"; 
	srvc.submitLabelText = [NSString stringWithString: @"PHONE CALL"];
	[self.navigationController pushViewController:srvc animated:YES];
	[srvc release];
}
- (IBAction) checkPatientAction: (id) sender {
	SetReminderViewController* srvc = [[SetReminderViewController alloc] initWithNibName:nil bundle:nil];
	srvc.title = @"Check patient reminder"; 
	srvc.submitLabelText = [NSString stringWithString: @"CHECK PATIENT"];
	[self.navigationController pushViewController:srvc animated:YES];
	[srvc release];
}
- (IBAction) checkResultsAction: (id) sender {
	SetReminderViewController* srvc = [[SetReminderViewController alloc] initWithNibName:nil bundle:nil];
	srvc.title = @"Check result reminder"; 
	srvc.submitLabelText = [NSString stringWithString: @"CHECK RESULT"];
	[self.navigationController pushViewController:srvc animated:YES];
	[srvc release];
}
- (IBAction) viewRemindersAction: (id) sender {
	RemindersListViewController* rlvc = [[RemindersListViewController alloc] initWithNibName:nil bundle:nil];
	[self.navigationController pushViewController:rlvc animated:YES];
	[rlvc release];
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
